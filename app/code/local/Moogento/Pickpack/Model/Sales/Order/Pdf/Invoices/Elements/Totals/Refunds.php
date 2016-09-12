<?php

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals_Refunds extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals
{
    public function caculateRefunds(&$total_data){
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $totals_object = $this->getPdf();

        $result = array();

        $value = $order->getTotalRefunded();
        if ($this->packingsheetConfig['hide_zero_discount_value'] == 0 || $value != 0){
            $result[0]['key'] = 'refunds';
            $result[0]['text'] = $helper->__('Refunds');
            $result[0]['value'] = -floatval($value);
            $result[0]['incl'] = false;
            //add base currency value
            if ($totals_object->showBaseValue){
                $result[0]['base_value'] = -floatval($order->getData('base_total_refunded'));
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