<?php

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals_Subinfo extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals
{
    public function getSubInfo($total_data){
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $totals_object = $this->getPdf();

        $result = array();

        if ($this->packingsheetConfig['total_paid_yn'] == 1)
            $result[] = $this->getTotalPaid();

        // if customer using magento grandtotal value then total_refunded will print like subinfog and will not add to grand_total value
        if (($order->getTotalRefunded() != 0) && ($this->packingsheetConfig['total_default_grandtotal_yn'] == 1))
            $result[] = $this->getTotalRefunds();

        if ($this->packingsheetConfig['total_due_yn'] == 1)
            $result[] = $this->getTotalDue();

        return $result;
    }

    // this will use to get total due, it is total_sub_info
    private function getTotalDue(){
        $total_due = array();
        $order = $this->getOrder();
        $helper = Mage::helper('pickpack');
        $totals_object = $this->getPdf();

        if ($this->packingsheetConfig['total_paid_yn'] == 1) {
            $total_due['key'] = 'sub_info';
            $total_due['text'] = $helper->__('Total Due');
            $value = $order->getTotalDue();
            if (is_null($value))
                $value = 0;
            $total_due['value'] = $value;
            if ($totals_object->showBaseValue){
                $base_value = $order->getBaseTotalDue();
                if (is_null($base_value))
                    $base_value = 0;
                $total_due['base_value'] = $base_value;
            }
        }

        return $total_due;
    }

    // this will use to get total retund, it is total_sub_info
    private function getTotalRefunds(){
        $total_refunded = array();
        $order = $this->getOrder();
        $helper = Mage::helper('pickpack');
        $totals_object = $this->getPdf();

        $total_refunded['key'] = 'sub_info';
        $total_refunded['text'] = $helper->__('Total Refunded');
        $value = $order->getTotalRefunded();
        if (is_null($value))
            $value = 0;
        $total_refunded['value'] = $value;
        if ($totals_object->showBaseValue){
            $base_value = $order->getBaseTotalRefunded();
            if (is_null($base_value))
                $base_value = 0;
            $total_refunded['base_value'] = $base_value;
        }

        return $total_refunded;
    }

    // this will use to get total paid, it is total_sub_info
    private function getTotalPaid(){
        $total_paid = array();
        $order = $this->getOrder();
        $helper = Mage::helper('pickpack');
        $totals_object = $this->getPdf();

        if ($this->packingsheetConfig['total_due_yn'] == 1) {
            $total_paid['key'] = 'sub_info';
            $total_paid['text'] = $helper->__('Total Paid');
            $value = $order->getTotalPaid();
            if (is_null($value))
                $value = 0;
            $total_paid['value'] = $value;
            if ($totals_object->showBaseValue){
                $base_value = $order->getBaseTotalPaid();
                if (is_null($base_value))
                    $base_value = 0;
                $total_paid['base_value'] = $base_value;
            }
        }

        return $total_paid;
    }
}