<?php

class Unirgy_DropshipMulti_Model_InventoryObserver extends Mage_CatalogInventory_Model_Observer
{
    protected $_udmultiQuoteItem;
    public function getUdmultiQuoteItem()
    {
        return $this->_udmultiQuoteItem;
    }
    public function getUdropshipVendor()
    {
        return $this->_udmultiQuoteItem && $this->_udmultiQuoteItem->getUdropshipVendor()
            ? $this->_udmultiQuoteItem->getUdropshipVendor() : null;
    }
    protected function _getProductQtyForCheck($productId, $itemQty)
    {
        if (!Mage::helper('udmulti')->isActive()) {
            return parent::_getProductQtyForCheck($productId, $itemQty);
        }
        $qty = $itemQty;
        $pidKey = $this->getUdropshipVendor() ? $this->getUdropshipVendor().'-'.$productId : $productId;
        if (isset($this->_checkedProductsQty[$pidKey])) {
            $qty += $this->_checkedProductsQty[$pidKey];
        }
        $this->_checkedProductsQty[$pidKey] = $qty;
        return $qty;
    }
    protected function _getQuoteItemQtyForCheck($productId, $quoteItemId, $itemQty)
    {
        if (!Mage::helper('udmulti')->isActive()) {
            return parent::_getQuoteItemQtyForCheck($productId, $quoteItemId, $itemQty);
        }
        $qty = $itemQty;
        $pidKey = $this->getUdropshipVendor() ? $this->getUdropshipVendor().'-'.$productId : $productId;
        if (isset($this->_checkedQuoteItems[$pidKey]['qty']) &&
            !in_array($quoteItemId, $this->_checkedQuoteItems[$pidKey]['items'])) {
                $qty += $this->_checkedQuoteItems[$pidKey]['qty'];
        }

        $this->_checkedQuoteItems[$pidKey]['qty'] = $qty;
        $this->_checkedQuoteItems[$pidKey]['items'][] = $quoteItemId;

        return $qty;
    }
    public function checkQuoteItemQty($observer)
    {
        if (!Mage::helper('udmulti')->isActive()) {
            return parent::checkQuoteItemQty($observer);
        }
        /*
        $this->_udmultiQuoteItem = $observer->getEvent()->getItem();
        $result = parent::checkQuoteItemQty($observer);
        $this->_udmultiQuoteItem = null;
        */
        return $this;
    }

    public function subtractQuoteInventory(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('udmulti')->isActive()) {
            return parent::subtractQuoteInventory($observer);
        }
        $quote = $observer->getEvent()->getQuote();

        // Maybe we've already processed this quote in some event during order placement
        // e.g. call in event 'sales_model_service_quote_submit_before' and later in 'checkout_submit_all_after'
        if ($quote->getInventoryProcessed()) {
            return;
        }
        $update = array();
        $allPids = array();
        foreach ($quote->getAllItems() as $item) {
            if (!$item->getChildren()) {
                $pId = $item->getProductId();
                $vId = $item->getUdropshipVendor();
                $v = Mage::helper('udropship')->getVendor($vId);
                if (!$v->getId() && $v->getStockcheckMethod()) continue;
                $allPids[$pId] = $pId;
                if (isset($update[$vId][$pId])) {
                    $update[$vId][$pId]['stock_qty_add'] -= $item->getTotalQty();
                } else {
                    $update[$vId][$pId] = array(
                        'stock_qty_add' => -$item->getTotalQty(),
                    );
                }
            }
        }

        if (empty($allPids)) {
            return $this;
        }

        /** @var Unirgy_Dropship_Model_Mysql4_Helper $rHlp */
        $rHlp = Mage::getResourceSingleton('udropship/helper');
        $catHlp = Mage::helper('udropship/catalog');
        $hlpm = Mage::helper('udmulti');
        $conn = $rHlp->getReadConnection();
        $write = $rHlp->getWriteConnection();

        $mvDataFlatSel = $conn->select()
            ->from(array('vp'=>$rHlp->getTable('udropship/vendor_product')), array('vendor_id','product_id','vendor_product_id'))
            ->where('product_id in (?)', $allPids);

        $mvDataFlat = $conn->fetchAll($mvDataFlatSel);
        $updateFlat = array();
        foreach ($update as $vId=>$_update) {
            foreach ($_update as $pId => $__ud) {
                foreach ($mvDataFlat as $__mvdf) {
                    if ($__mvdf['product_id']==$pId && $__mvdf['vendor_id']==$vId) {
                        $updateFlat[$__mvdf['vendor_product_id']] = $__ud;
                        break;
                    }
                }
            }
        }

        $siData = Mage::getResourceSingleton('udropship/helper')->loadDbColumns(
            Mage::getModel('cataloginventory/stock_item'),
            array('product_id'=>$allPids),
            array('backorders','use_config_backorders')
        );

        $rHlp->beginTransaction();

        $mvData = $rHlp->loadDbColumnsForUpdate(
            Mage::getModel('udropship/vendor_product'),
            array_keys($updateFlat),
            array('backorders','stock_qty','product_id','vendor_id','avail_state','avail_date','status')
        );

        foreach ($updateFlat as $__vpId=>$__ud) {
            $qtyCheck = abs($__ud['stock_qty_add']);
            if (!array_key_exists($__vpId, $mvData)) {
                if (Mage::app()->getStore()->isAdmin()) continue;
                Mage::throwException(
                    Mage::helper('udropship')->__('Stock configuration problem')
                );
            }
            $_mv = $mvData[$__vpId];
            $pId = $_mv['product_id'];
            $vId = $_mv['vendor_id'];
            if (!$hlpm->isQtySalableByVendorData($qtyCheck, (array)@$siData[$pId], $vId, $_mv)) {
                if (Mage::app()->getStore()->isAdmin()) continue;
                Mage::throwException(
                    Mage::helper('udropship')->__('Not all products are available in the requested quantity')
                );
            }
        }
        $conditions = array();
        foreach ($updateFlat as $__vpId=>$__ud) {
            $case = $conn->quoteInto('?', $__vpId);
            $result = $conn->quoteInto("stock_qty-?", abs($__ud['stock_qty_add']));
            $conditions[$case] = $result;
        }
        $value = $catHlp->getCaseSql('vendor_product_id', $conditions, 'stock_qty');
        $where = array('vendor_product_id IN (?)' => array_keys($updateFlat));
        $write->update($rHlp->getTable('udropship/vendor_product'), ['stock_qty' => $value], $where);

        $rHlp->commit();

        $quote->setInventoryProcessed(true);
        return $this;
    }

    public function revertQuoteInventory($observer)
    {
        if (!Mage::helper('udmulti')->isActive()) {
            return parent::revertQuoteInventory($observer);
        }
        $quote = $observer->getEvent()->getQuote();

        if (!$quote->getInventoryProcessed()) {
            return;
        }
        $update = array();
        foreach ($quote->getAllItems() as $item) {
            if (!$item->getChildren()) {
                $pId = $item->getProductId();
                $vId = $item->getUdropshipVendor();
                if (isset($update[$vId][$pId])) {
                    $update[$vId][$pId]['stock_qty_add'] += $item->getTotalQty();
                } else {
                    $update[$vId][$pId] = array(
                        'stock_qty_add' => $item->getTotalQty(),
                    );
                }
            }
        }

        foreach ($update as $vId=>$_update) {
            Mage::helper('udmulti')->setReindexFlag(false);
            Mage::helper('udmulti')->saveThisVendorProductsPidKeys($_update, $vId);
            Mage::helper('udmulti')->setReindexFlag(true);
        }

        $quote->setInventoryProcessed(false);
    }

    public function cancelOrderItem($observer)
    {
        if (!Mage::helper('udmulti')->isActive()) {
            return parent::cancelOrderItem($observer);
        }
        $item = $observer->getEvent()->getItem();

        $children = $item->getChildrenItems();
        $qty = $item->getQtyOrdered() - max($item->getQtyShipped(), $item->getQtyInvoiced()) - $item->getQtyCanceled();

        if ($item->getId() && ($productId = $item->getProductId()) && empty($children)) {
            $qty = $item->getQtyOrdered() - $item->getQtyCanceled();
            $parentItem = $item->getParentItem();
            $qtyInvoiced = $qtyShipped = 0;
            if ($item->isDummy(true) && $parentItem) {
                $parentQtyShipped = $parentItem->getQtyShipped();
                $parentQtyOrdered = $parentItem->getQtyOrdered();
                $parentQtyOrdered = $parentQtyOrdered > 0 ? $parentQtyOrdered : 1;
                $qtyShipped = $parentQtyShipped*$item->getQtyOrdered()/$parentQtyOrdered;
            } elseif (!$item->isDummy(true)) {
                $qtyShipped = $item->getQtyShipped();
            }
            if ($item->isDummy() && $parentItem) {
                $parentQtyInvoiced = $parentItem->getQtyInvoiced();
                $parentQtyOrdered = $parentItem->getQtyOrdered();
                $parentQtyOrdered = $parentQtyOrdered > 0 ? $parentQtyOrdered : 1;
                $qtyInvoiced = $parentQtyInvoiced*$item->getQtyOrdered()/$parentQtyOrdered;
            } elseif (!$item->isDummy()) {
                $qtyInvoiced = $item->getQtyInvoiced();
            }
            $qty -= max($qtyShipped, $qtyInvoiced);
            if ($qty>0) {
                Mage::helper('udmulti')->saveThisVendorProductsPidKeys(
                    array($productId=>array('stock_qty_add'=>$qty)),
                    $item->getUdropshipVendor()
                );
            }
        }

        return $this;
    }

    public function refundOrderInventory($observer)
    {
        if (!Mage::helper('udmulti')->isActive()) {
            return parent::refundOrderInventory($observer);
        }
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $parentItems = $items = array();
        
        foreach ($creditmemo->getAllItems() as $item) {
            $return = false;
            if ($item->hasBackToStock()) {
                if ($item->getBackToStock() && $item->getQty()) {
                    $return = true;
                }
            } elseif (Mage::helper('cataloginventory')->isAutoReturnEnabled()) {
                $return = true;
            }
            $oItem = $item->getOrderItem();
            $children = $oItem->getChildrenItems() ? $oItem->getChildrenItems() : $oItem->getChildren();
            if (($oParent = $oItem->getParentItem())) {
                $parentItem = @$parentItems[$oParent->getId()];
            } else {
                $parentItem = null;
            }
            if ($children) {
                $parentItems[$oItem->getId()] = $item;
            } elseif ($return && ($vId = $oItem->getUdropshipVendor())) {
                $qty = null;
                if ($oItem->isDummy() && $parentItem) {
                    $parentQtyOrdered = $parentItem->getOrderItem()->getQtyOrdered();
                    $parentQtyOrdered = $parentQtyOrdered > 0 ? $parentQtyOrdered : 1;
                    $qty = $parentItem->getQty()*$oItem->getQtyOrdered()/$parentQtyOrdered;
                } elseif (!$oItem->isDummy()) {
                    $qty = $item->getQty();;
                }
                if ($qty !== null) {
                    if (isset($items[$vId][$item->getProductId()])) {
                        $items[$vId][$item->getProductId()]['stock_qty_add'] += $qty;
                    } else {
                        $items[$vId][$item->getProductId()] = array(
                            'stock_qty_add' => $qty,
                        );
                    }
                }
            }
        }
        if (!empty($items)) {
            $reindexPids = array();
            foreach ($items as $vId=>$update) {
                $reindexPids = array_merge($reindexPids, array_keys($update));
                Mage::helper('udmulti')->setReindexFlag(false);
                Mage::helper('udmulti')->saveThisVendorProductsPidKeys($update, $vId);
                Mage::helper('udmulti')->setReindexFlag(true);
            }
            $reindexPids = array_unique($reindexPids);
            $indexer = Mage::getSingleton('index/indexer');
            $pAction = Mage::getModel('catalog/product_action');
            $idxEvent = Mage::getModel('index/event')
                ->setEntity(Mage_Catalog_Model_Product::ENTITY)
                ->setType(Mage_Index_Model_Event::TYPE_MASS_ACTION)
                ->setDataObject($pAction);
            /* hook to cheat index process to be executed */
            $pAction->setWebsiteIds(array(0));
            $pAction->setProductIds($reindexPids);
            $indexer->getProcessByCode('cataloginventory_stock')->register($idxEvent)->processEvent($idxEvent);
            $indexer->getProcessByCode('catalog_product_price')->register($idxEvent)->processEvent($idxEvent);
            $indexer->getProcessByCode('udropship_vendor_product_assoc')->register($idxEvent)->processEvent($idxEvent);
        }
        //Mage::getSingleton('cataloginventory/stock')->revertProductsSale($items);
    }
}