<?php

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals_Tax extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals
{
    public function calculateTax(&$total_data){
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $totals_object = $this->getPdf();

        $result = array();

        if ($this->packingsheetConfig['total_tax_yn'] == 1){
            if($this->packingsheetConfig['filter_items_by_status'] == 1){
                $value = $order->getTaxInvoiced();
            }else{
                $value = $order->getTaxAmount();
            }
            if ($this->packingsheetConfig['hide_zero_tax_value'] == 0 || $value != 0){
                $result[0]['key'] = 'tax';
                $result[0]['text'] = $helper->__('Tax Label');
				$result[0]['incl'] = false;
				if ( ($this->packingsheetConfig['total_tax_incl_yn'] == 1) || ($this->packingsheetConfig['total_subtotal_with_tax_yn'] == 1) ) {
                    $result[0]['incl'] = true;
					$result[0]['text'] = $helper->__('Tax Label Incl.');
				}
                $result[0]['value'] = $value;
                if ($totals_object->showBaseValue){
                    $result[0]['base_value'] = $order->getBaseTaxAmount();
                }
            }
        }elseif ($this->packingsheetConfig['total_tax_yn'] == 2){
            if ($this->packingsheetConfig['total_tax_breakdown_yn'] == 1){
                $tax_info = $order->getFullTaxInfo();

                foreach ($tax_info as $item){
                    $value = $item['amount'];
                    if ($this->packingsheetConfig['hide_zero_tax_value'] == 0 || $value != 0){
                        $tax_item = array();
                        $tax_item['key'] = 'tax';
                        $tax_item['text'] = $item['rates'][0]['title'];
						$tax_item['incl'] = false;
						if ( ($this->packingsheetConfig['total_tax_incl_yn'] == 1) || ($this->packingsheetConfig['total_subtotal_with_tax_yn'] == 1) ) {
		                    $tax_item['incl'] = true;
							$tax_item['text'] = $item['rates'][0]['title']. ' ' . $helper->__('Incl.');
						}
                        $tax_item['value'] = $value;
                        if ($totals_object->showBaseValue){
                            $tax_item['base_value'] = $item['base_amount'];;
                        }
                        $result[] = $tax_item;
                    }
                }
            }else{
                $value = $total_data['subtotal_tax'];
                if ($this->packingsheetConfig['hide_zero_tax_value'] == 0 || $value != 0){
                    $result[0]['key'] = 'tax';
                    $result[0]['text'] = $helper->__('Tax Label');
					$result[0]['incl'] = false;
					if ( ($this->packingsheetConfig['total_tax_incl_yn'] == 1) || ($this->packingsheetConfig['total_subtotal_with_tax_yn'] == 1) ) {
	                    $result[0]['incl'] = true;
                        $result[0]['text'] = $helper->__('Tax Label Incl.');
					}
                    $result[0]['value'] = $value;
                    if ($totals_object->showBaseValue){
                        $result[0]['base_value'] = $total_data['base_subtotal_tax'];
                    }
                }
            }
        }

        //add value to grand total
        if (count($result)){
            foreach ($result as $tax){
                if (isset($total_data['grand_total']))
                    $total_data['grand_total'] += floatval($tax['value']);
                else
					$total_data['grand_total'] = floatval($tax['value']);
                //add base currency value
                if ($totals_object->showBaseValue){
                    if (isset($total_data['base_grand_total']))
                        $total_data['base_grand_total'] += floatval($tax['base_value']);
                    else
                        $total_data['base_grand_total'] = floatval($tax['base_value']);
                }
            }
        }

        return $result;
    }
}