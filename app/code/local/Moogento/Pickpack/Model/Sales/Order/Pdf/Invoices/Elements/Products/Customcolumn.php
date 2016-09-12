<?php
class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Products_Customcolumn extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Products
{
    public function __construct($arguments) {
        parent::__construct($arguments);
    }

    public function printSalesIgniterRentalColumn (){
        $item = $this->product_build_value['item']; //$value;
        $options = $item->getProductOptions();

        if (isset($options['info_buyRequest'][ITwebexperts_Payperrentals_Model_Product_Type_Reservation::START_DATE_OPTION])) {
            $start_date = $options['info_buyRequest'][ITwebexperts_Payperrentals_Model_Product_Type_Reservation::START_DATE_OPTION];
            $end_date = $options['info_buyRequest'][ITwebexperts_Payperrentals_Model_Product_Type_Reservation::END_DATE_OPTION];


            $start_date = Mage::helper('payperrentals')->__('Start Date: ').$start_date;
            $end_date = Mage::helper('payperrentals')->__('End Date: ').$end_date;

            $temp_y = $this->y;
            $this->_drawText($start_date, ($this->packingsheetConfig['show_sales_igniter_rental_date_xpos']), $temp_y);
            $line_height = (1.5 * $this->generalConfig['font_size_body']);
            $temp_y -= $line_height;
            $this->_drawText($end_date, ($this->packingsheetConfig['show_sales_igniter_rental_date_xpos']), $temp_y);
            $temp_y -= $line_height;

            if (is_null($this->next_product_line_ypos) || $this->next_product_line_ypos > $temp_y)
                $this->next_product_line_ypos = $temp_y;
        }
    }
}