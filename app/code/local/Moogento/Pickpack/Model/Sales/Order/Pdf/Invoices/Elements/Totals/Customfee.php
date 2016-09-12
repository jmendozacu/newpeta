<?php

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals_Customfee extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals
{
    public function caculateCustomFee(&$total_data){
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $totals_object = $this->getPdf();

        $result = array();

        /****CUSTOM CODE TO ADD FEE OF Magesty_AddFees EXTENSION****/
        if (Mage::helper('pickpack')->isinstalled('Magesty_AddFees')){
            if ($order->getData('addfees')){
                $fee = array();
                $fee['key'] = 'custom_fee';
                $fee['text'] = Mage::helper('addfees')->getTotalsLabel();
                $fee['value'] = floatval($order->getData('addfees'));
                $result[] = $fee;
                unset($fee);
            }
        }
        /****END CUSTOM CODE TO ADD FEE OF Magesty_AddFees EXTENSION****/

        /****CUSTOM CODE TO ADD Cash on Delivery FEE****/
        if ($order->getData('cod_fee')){
            $cod_fee_value = floatval($order->getData('cod_fee'));
            if ($order->getData('cod_tax_amount'))
                $cod_fee_value += floatval($order->getData('cod_tax_amount'));
            $fee = array();
            $fee['key'] = 'custom_fee';
            $fee['text'] = $helper->__('Cash on Delivery');
            $fee['value'] = $cod_fee_value;
            $result[] = $fee;
            unset($fee);
        }

        //add base currency value
        if ($totals_object->showBaseValue){
            foreach ($result as $key => $item){
                $result[$key]['base_value'] = $totals_object->getBaseCurrencyValue($item['value']);
            }
        }

        //add value to grand total
        if (count($result)){
            foreach ($result as $customfee){
                if (isset($total_data['grand_total']))
                    $total_data['grand_total'] += floatval($customfee['value']);
                else 
					$total_data['grand_total'] = floatval($customfee['value']);
            }
        }

        return $result;
    }
}