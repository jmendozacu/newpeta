<?php
/**
 * Date: 4/7/2016
 * Time: 11:28 AM
 */

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals_Shipping extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals
{
    public function caculateShipping(&$total_data){
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $totals_object = $this->getPdf();

        $result = array();

        if ($this->packingsheetConfig['total_shipping_yn'] != 0){
            if($this->packingsheetConfig['filter_items_by_status'] == 1)
                $value = $order->getShippingInvoiced();
            else
                $value = $order->getShippingAmount();
            $shipping_tax = floatval($order->getData('shipping_tax_amount'));

            if ($this->packingsheetConfig['total_shipping_yn'] == 1){
                $magento_config_shipping_tax = Mage::getStoreConfig('tax/sales_display/shipping', $storeId);
                if ($magento_config_shipping_tax == 2 || $magento_config_shipping_tax == 3){
                    $value += $shipping_tax;
                }
            }elseif ($this->packingsheetConfig['total_shipping_yn'] == 2){
                if ($this->packingsheetConfig['total_shipping_with_tax_yn'] == 1)
                    $value += $shipping_tax;
            }

            if( ($this->packingsheetConfig['hide_zero_shipping_value'] == 0) || ($value != 0) ){
                $result[0]['key'] = 'shipping';
                $result[0]['text'] = $helper->__('Shipping');
                $result[0]['value'] = $value;
                //add base currency value
                if ($totals_object->showBaseValue){
                    $base_value = $order->getBaseShippingAmount();
                    $base_shipping_tax = floatval($order->getData('base_shipping_tax_amount'));
                    if ($this->packingsheetConfig['total_shipping_yn'] == 1){
                        $magento_config_shipping_tax = Mage::getStoreConfig('tax/sales_display/shipping', $storeId);
                        if ($magento_config_shipping_tax == 2 || $magento_config_shipping_tax == 3){
                            $base_value += $base_shipping_tax;
                        }
                    }elseif ($this->packingsheetConfig['total_shipping_yn'] == 2){
                        if ($base_value->packingsheetConfig['total_shipping_with_tax_yn'] == 1)
                            $value += $base_shipping_tax;
                    }
                    $result[0]['base_value'] = $base_value;
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