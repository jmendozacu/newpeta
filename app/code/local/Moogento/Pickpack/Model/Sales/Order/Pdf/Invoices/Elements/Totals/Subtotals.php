<?php

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals_Subtotals extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals
{
    public function caculateSubtotals(&$total_data){
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $totals_object = $this->getPdf();

        $result = array();

        if ($this->packingsheetConfig['total_subtotal'] == 1){
            $result[0]['key'] = 'subtotal';
            $result[0]['text'] = $helper->__('Subtotal');
            $result[0]['value'] = ($this->packingsheetConfig['filter_items_by_status'] == 1)?$order->getSubtotalInvoiced():$order->getSubtotal();
            if ($totals_object->showBaseValue){
                $result[0]['base_value'] = $order->getBaseSubtotal();
            }
        }elseif ($this->packingsheetConfig['total_subtotal'] == 2){
            $result[0]['key'] = 'subtotal';
            $result[0]['text'] = $helper->__('Subtotal');
            $result[0]['value'] = $total_data['subtotal_original_price'];
			
            if ($this->packingsheetConfig['total_subtotal_with_tax_yn'] == 1){
                $result[0]['value'] = $total_data['subtotal_original_price'] + $total_data['subtotal_tax'];
                if ($totals_object->showBaseValue) {
                    $result[0]['base_value'] = $total_data['base_subtotal_original_price'] + $total_data['base_subtotal_tax'];
                }
            }else{
                $result[0]['value'] = $total_data['subtotal_original_price'];
                if ($totals_object->showBaseValue) {
                    $result[0]['base_value'] = $total_data['base_subtotal_original_price'];
                }
            }

            if ($this->packingsheetConfig['total_subtotal_with_discount_yn'] == 1){
                $result[0]['value'] -= floatval($total_data['subtotal_discount']);
                if ($totals_object->showBaseValue){
                    $result[0]['base_value'] -= floatval($total_data['base_subtotal_discount']);
                }
            }
        }

        //add value to grand total
        if (count($result)){
            if (isset($total_data['grand_total']))
				$total_data['grand_total'] += floatval($result[0]['value']);
            else
				$total_data['grand_total'] = floatval($result[0]['value']);
            //add base currency value
            if ($totals_object->showBaseValue){
                if (isset($total_data['base_grand_total']))
                    $total_data['base_grand_total'] += floatval($result[0]['base_value']);
                else
                    $total_data['base_grand_total'] = floatval($result[0]['base_value']);
            }
        }

        return $result;
    }
}