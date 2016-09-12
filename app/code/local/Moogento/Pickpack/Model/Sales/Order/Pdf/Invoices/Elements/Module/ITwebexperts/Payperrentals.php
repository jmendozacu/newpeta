<?php
/**
 * Date: 09/06/2016
 * Time: 3:43 CH
 */
class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Module_ITwebexperts_Payperrentals extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Module_Abstract
{
    public function printSingleDate(){
        $order = $this->getOrder();
        $page = $this->getPage();

        $isSingle = ITwebexperts_Payperrentals_Helper_Data::isSingleOrder($order);
        if($isSingle['bool']){
            $str = Mage::helper('payperrentals')->__('Start Date:')
                . ' ' . $isSingle['start_date']
                . ' ' . Mage::helper('payperrentals')->__('End Date:')
                . ' ' . $isSingle['end_date'];

            $this->_setFont($page, $this->generalConfig['font_style_body'], ($this->generalConfig['font_size_body'] - 1), $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->generalConfig['font_color_body']);
            $page->drawText($str, $this->addressXY[0], $this->y, 'UTF-8');

            $this->y -= 1.5 * $this->generalConfig['font_size_body'];
            $this->subheader_start -= 1.5 * $this->generalConfig['font_size_body'];
            $this->is_print_ITwebexperts_Payperrentals_Date = true;
        }else $this->is_print_ITwebexperts_Payperrentals_Date = false;
    }
}