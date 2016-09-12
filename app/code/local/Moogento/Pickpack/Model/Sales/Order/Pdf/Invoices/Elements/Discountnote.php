<?php
/**
 *
 * Date: 29.11.15
 * Time: 13:23
 */

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Discountnote extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Abstract
{
    public $generalConfig = array();
    public $y;

    public function showDiscountNote($message, $y)
    {
        if(strlen($message) == 0 || $message == '') {
            return;
        }

        $page = $this->getPage();
        $order = $this->getOrder();
        $pageConfig = $this->getPageConfig();
        $full_page_width = $pageConfig['full_page_width'];
        $this->y = $y;
        $savedAmount  = Mage::helper('core')->currency(abs($order->getDiscountAmount()), true, false);
        $message = str_replace("{{amount}}", $savedAmount, $message);

        $pageConfig = $this->getPageConfig();
        $generalConfig = $this->getGeneralConfig();
        $font_family_choice = $generalConfig['font_family_gift_message'];
        $font_size_choice = $generalConfig['font_size_gift_message'];
        $font_color_choice = $generalConfig['font_color_gift_message'];
        $this->_setFont($page, 'bold', $font_size_choice, $font_family_choice, $generalConfig['non_standard_characters'], $font_color_choice);
        if (abs($order->getDiscountAmount()) > 0) {
            $page->drawText($message, ($pageConfig['padded_left'] + ($full_page_width / 2)) - 100, $this->y, 'UTF-8');
        }
    }
}