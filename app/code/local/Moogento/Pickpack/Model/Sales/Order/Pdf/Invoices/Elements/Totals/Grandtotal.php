<?php

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals_Grandtotal extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals
{
    public function caculateGrandTotal(&$total_data){
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $totals_object = $this->getPdf();

        if ($this->packingsheetConfig['total_default_grandtotal_yn'] == 1){
            $result[0]['key'] = 'grand_total';
            $result[0]['text'] = $helper->__('Grand Total');
            $result[0]['value'] = ($this->packingsheetConfig['filter_items_by_status'] == 1)?$order->getTotalInvoiced():$order->getGrandTotal();
            if ($totals_object->showBaseValue) {
                $result[0]['base_value'] = $order->getBaseGrandTotal();
            }
        } elseif ($this->packingsheetConfig['total_default_grandtotal_yn'] == 0){
            $result[0]['key'] = 'grand_total';
            $result[0]['text'] = $helper->__('Grand Total');
			
            if (isset($total_data['grand_total']))
                $result[0]['value'] = $total_data['grand_total'];
			else
                $result[0]['value'] = $order->getGrandTotal();

            if ($totals_object->showBaseValue) {
                if (isset($total_data['grand_total']))
                    $result[0]['base_value'] = $total_data['base_grand_total'];
                else
                    $result[0]['base_value'] = $order->getBaseGrandTotal();
            }
        }

        return $result;
    }
}