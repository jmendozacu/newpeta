<?php

/**
 * Moogento
 *
 * SOFTWARE LICENSE
 *
 * This source file is covered by the Moogento End User License Agreement
 * that is bundled with this extension in the file License.html
 * It is also available online here:
 * https://moogento.com/License.html
 *
 * NOTICE
 *
 * If you customize this file please remember that it will be overwrtitten
 * with any future upgrade installs.
 * If you'd like to add a feature which is not in this software, get in touch
 * at www.moogento.com for a quote.
 *
 * ID          pe+sMEDTrtCzNq3pehW9DJ0lnYtgqva4i4Z=
 * File        Label.php
 * @category   Moogento
 * @package    pickPack
 * @copyright  Copyright (c) 2016 Moogento <info@moogento.com> / All rights reserved.
 * @license    https://moogento.com/License.html
 */
class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Label extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices
{
    private function setAddressLabelConfig($order){
        $helper = Mage::helper('pickpack');
        $storeId = $order->getStore()->getId();
        $wonder = 'label';

        $this->current_order = $order;
        $this->current_store_id = $storeId;

        $this->generalConfig = Mage::helper('pickpack/config')->getGeneralConfigArray($storeId);
        $this->addressLabelConfig = Mage::helper('pickpack/config')->getAddressLabelConfigArray($storeId);

        if ($this->addressLabelConfig['page_size'] == 'a4'){
            $this->paper_width = 612;
            $this->paper_height = 792;
        }elseif ($this->addressLabelConfig['page_size'] == 'letter'){
            $this->paper_width = 595;
            $this->paper_height = 842;
        }

        $this->page_top = $this->paper_height - $this->addressLabelConfig['paper_margin'][0];
        $this->page_right = $this->paper_width - $this->addressLabelConfig['paper_margin'][1];
        $this->page_bottom = $this->addressLabelConfig['paper_margin'][2];
        $this->page_left = $this->addressLabelConfig['paper_margin'][3];

        $this->label_width = $this->addressLabelConfig['label_width'];
        $this->label_height = $this->addressLabelConfig['label_height'];
        $this->label_block_width = $this->label_width + $this->addressLabelConfig['label_padding'][1] + $this->addressLabelConfig['label_padding'][3];
        $this->label_block_height = $this->label_height + $this->addressLabelConfig['label_padding'][0] + $this->addressLabelConfig['label_padding'][2];
    }

    public function getOrder(){
        return $this->current_order;
    }

    public function getStoreId(){
        return $this->current_store_id;
    }

    public function newPageLabel(array $settings = array()) {
        $pageSize = $this->paper_width.':'.$this->paper_height;
        $page = $this->_getPdf()->newPage($pageSize);

        $this->_getPdf()->pages[] = $page;
        $this->setCurrentPage($page);
        $this->y = $this->page_top;

        return $page;
    }

    // this function will calcuate new position for new label block
    public function newLabelBlock(){
        if (is_null($this->current_label_block_top)){
            $this->newPageLabel();
            //current_label_block_ will include padding
            $this->current_label_block_top = $this->page_top;
            $this->current_label_block_right = $this->page_left + $this->label_block_width;
            $this->current_label_block_left = $this->page_left;
            $this->current_label_block_bottom = $this->page_top - $this->label_block_height;
        }else{
            //check if need to create new pdf
            if ($this->current_label_block_bottom - $this->label_block_height < $this->page_bottom){
                if ($this->current_label_block_right + $this->label_block_width > $this->page_right){
                    //create new page
                    $this->newPageLabel();
                    $this->current_label_block_top = $this->page_top;
                    $this->current_label_block_right = $this->page_left + $this->label_block_width;
                    $this->current_label_block_left = $this->page_left;
                    $this->current_label_block_bottom = $this->page_top - $this->label_block_height;
                }else{
                    //move to top left
                    $this->current_label_block_top = $this->page_top;
                    $this->current_label_block_left = $this->current_label_block_right;
                    $this->current_label_block_right = $this->current_label_block_right + $this->label_block_width;
                    $this->current_label_block_bottom = $this->page_top - $this->label_block_height;
                }
            }else{
                //move position to below current label block
                $this->current_label_block_top = $this->current_label_block_bottom;
                $this->current_label_block_bottom = $this->current_label_block_top - $this->label_block_height;
            }
        }

        //set acture value that label can be print
        $this->current_label_top = $this->current_label_block_top - $this->addressLabelConfig['label_padding'][0];
        $this->current_label_right = $this->current_label_block_right - $this->addressLabelConfig['label_padding'][1];
        $this->current_label_bottom = $this->current_label_block_bottom + $this->addressLabelConfig['label_padding'][2];
        $this->current_label_left = $this->current_label_block_left + $this->addressLabelConfig['label_padding'][3];

//        $page = $this->getPage();
//        $greyout_color = new Zend_Pdf_Color_GrayScale(0.6);
//        $page->setFillColor($greyout_color);
//        $page->setLineColor($greyout_color);
//        $page->drawRectangle($this->current_label_block_left, $this->current_label_block_bottom, $this->current_label_block_right, $this->current_label_block_top);
//        $greyout_color = new Zend_Pdf_Color_GrayScale(0.9);
//        $page->setFillColor($greyout_color);
//        $page->setLineColor($greyout_color);
//        $page->drawRectangle($this->current_label_left, $this->current_label_bottom, $this->current_label_right, $this->current_label_top);
//        $greyout_color = new Zend_Pdf_Color_GrayScale(0);
//        $page->setFillColor($greyout_color);
//        $page->setLineColor($greyout_color);

        $this->y = $this->current_label_top;
    }

    public function getLabel($orders = array()) {
        /*
        Paper Keywords and paper size in points

        Letter         612x792
        LetterSmall     612x792
        Tabloid         792x1224
        Ledger        1224x792
        Legal         612x1008
        Statement     396x612
        Executive     540x720
        A0               2384x3371
        A1              1685x2384
        A2        1190x1684
        A3         842x1190
        A4         595x842
        A4Small         595x842
        A5         420x595
        B4         729x1032
        B5         516x729
        Envelope     ???x???
        Folio         612x936
        Quarto         610x780
        10x14         720x1008
        */

        $this->_beforeGetPdf();
        $this->_initRenderer('invoices');
        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);

        $helper = Mage::helper('pickpack');

        foreach ($orders as $orderSingle) {
            $order = $helper->getOrder($orderSingle);
            $order_id = $order->getRealOrderId();
            $storeId = $order->getStore()->getId();

            //this will set all config object for address label base on current order
            $this->setAddressLabelConfig($order);

            $useGFSLabel = false;
            if (Mage::helper('pickpack')->isInstalled('Moogento_CourierRules') && ($this->addressLabelConfig['use_courierrules_shipping_label'] == 1)) {
                $this->printCourierRulesLabel($orderSingle);
                $useGFSLabel = true;
            }
            if (!$useGFSLabel){
                $this->newLabelBlock();

                //print background for label
                if($this->addressLabelConfig['label_logo_yn2'])
                    $this->printShippingLabelImage();

                if ($this->addressLabelConfig['label_show_order_id_yn'])
                    $this->printOrderId();

                if ($this->addressLabelConfig['show_order_id_barcode_yn'])
                    $this->printOrderIdBarcode();

                if ($this->addressLabelConfig['label_return_address_yn'] == 'yesside')
                    $this->printReturnAddressTop();

                $this->printShippingAddress();

                if ($this->addressLabelConfig['show_address_barcode_yn'])
                    $this->printZipcodeBarcode();

                if ($this->addressLabelConfig['show_product_list'])
                    $this->printProductList();

                if ($this->addressLabelConfig['label_return_address_yn'] == '1')
                    $this->printReturnAddressOnNewLabel();
            }
        }

        $this->_afterGetPdf();
        return $pdf;
    }

    public function printReturnAddressTop(){
        $helper = Mage::helper('pickpack');
        $page = $this->getPage();

        $return_address = $this->addressLabelConfig['label_return_address'];

        $return_address_array = $this->wordWrapOnRealFont($return_address, $this->addressLabelConfig['font_family_return_label'], $this->addressLabelConfig['font_style_return_label'], $this->addressLabelConfig['font_size_return_label'], $this->addressLabelConfig['label_width']);

        $this->_setFont($page, $this->addressLabelConfig['font_style_return_label'], $this->addressLabelConfig['font_size_return_label'], $this->addressLabelConfig['font_family_return_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_return_label']);

        if (count($return_address_array)){
            $this->y -= 1.2 * $this->addressLabelConfig['font_size_return_label'];
        }
        foreach ($return_address_array as $return_address_line){
            $page->drawText($return_address_line, $this->current_label_left, $this->y, 'UTF-8');
            $this->y -= 1.2 * $this->addressLabelConfig['font_size_return_label'];
        }

    }

    public function printOrderIdBarcode(){
        $order = $this->getOrder();
        $page = $this->getPage();
        $black_color = Mage::helper('pickpack/config_color')->getPdfColor('black_color');

        $barcode_text = '';
        if($this->addressLabelConfig['show_order_id_barcode_yn'] == 1)
            $barcode_text = $order->getRealOrderId();
        elseif($this->addressLabelConfig['show_order_id_barcode_yn'] == 2) {
            if ($order->hasInvoices()) {
                $invIncrementIDs = array();
                foreach ($order->getInvoiceCollection() as $inv) {
                    $invIncrementIDs[] = $inv->getIncrementId();
                }
                $barcode_text = implode(',',$invIncrementIDs);
            }
        }

        $barcode_font_size = 16;
        $barcodeString = Mage::helper('pickpack/barcode')->convertToBarcodeString($barcode_text, $this->generalConfig['barcode_type']);
        $page->setFillColor($black_color);
        $page->setLineColor($black_color);
        $page->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0));
        $page->setFont(Zend_Pdf_Font::fontWithPath($this->action_path . $this->generalConfig['font_family_barcode']), $barcode_font_size);
        $this->order_barcode_x = $this->current_label_left + $this->addressLabelConfig['nudge_order_id_barcode'][0];
        $this->order_barcode_y = $this->y - 20 + $this->addressLabelConfig['nudge_order_id_barcode'][1];
        $page->drawText($barcodeString, $this->order_barcode_x, $this->order_barcode_y, 'CP1252');
        if($this->addressLabelConfig['label_show_order_id_yn'] != 1){
            $order_barcode_x = $this->order_barcode_x + 20;
            $order_barcode_y = $this->order_barcode_y;

            $order_id = '#'.$order->getRealOrderId();
            $white_color = new Zend_Pdf_Color_GrayScale(1);
            $order_id_in_barcode_font_size = $barcode_font_size - 5;

            $order_id_width = $this->widthForStringUsingFontSize($order_id, $this->addressLabelConfig['font_family_label'], $order_id_in_barcode_font_size, $this->addressLabelConfig['font_style_label'], $this->generalConfig['non_standard_characters']);
            $page->setFillColor($white_color);
            $page->setLineColor($white_color);
            $page->drawRectangle($order_barcode_x - 3, $order_barcode_y, $order_barcode_x + $order_id_width + 3, $order_barcode_y + 1.2 * $order_id_in_barcode_font_size);
            $page->setFillColor($black_color);
            $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $order_id_in_barcode_font_size, $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
            $page->drawText($order_id, $order_barcode_x, $order_barcode_y, 'UTF-8');
        }

        //return normal
        $page->setFillColor($black_color);
        Mage::helper('pickpack/font')->setFontRegular($page, $this->generalConfig['font_size_body']);

        $this->y = $this->order_barcode_y;
    }

    public function printOrderId(){
        $order = $this->getOrder();
        $page = $this->getPage();
        $white_color = new Zend_Pdf_Color_GrayScale(1);
        $black_color = Mage::helper('pickpack/config_color')->getPdfColor('black_color');

        $order_id = '#'.$order->getRealOrderId();
        $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'], $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
        $this->y -= 0.7 * $this->addressLabelConfig['font_size_label'];
        $order_barcode_x = $this->current_label_left + $this->addressLabelConfig['nudge_order_id'][0];
        $order_barcode_y = $this->y + $this->addressLabelConfig['nudge_order_id'][1];
        $page->drawText($order_id, $order_barcode_x, $order_barcode_y, 'UTF-8');
        $this->y = $order_barcode_y - 0.2 * $this->addressLabelConfig['font_size_label'];
    }

    public function printShippingAddress(){
        $page = $this->getPage();

        $address_array = $this->getShippingAddressArray();

        if (count($address_array)){
            $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'], $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
            $this->y -= 1.2 * $this->addressLabelConfig['font_size_label'];
            foreach ($address_array as $address_line){
                if ($this->y < $this->current_label_bottom){
                    $this->newLabelBlock();
                    $this->y -= 1.2 * $this->addressLabelConfig['font_size_label'];
                    $page = $this->getPage();
                    $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'], $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
                }
                $page->drawText($address_line, $this->current_label_left, $this->y, 'UTF-8');
                $this->y -= 1.2 * $this->addressLabelConfig['font_size_label'];
            }
        }
    }

    public function getShippingAddressArray(){
        $order = $this->getOrder();

        $shipping_address = $this->prepareShippingAddres();
        if (count($shipping_address) == 0)
            return array();

        if ($this->generalConfig['address_countryskip'] != '') {
            $address_shipping_countryskip = array();
            foreach( explode(',',$this->generalConfig['address_countryskip']) as $skip_country ){
                if ($skip_country == 'usa' || $skip_country == 'united states' || $skip_country == 'united states of america') {
                    $address_shipping_countryskip = array('usa', 'united states of america', 'united states');
                    break;
                }

                if( strtolower($skip_country) == strtolower($shipping_address['country']) ){
                    $address_shipping_countryskip = array($skip_country);
                    break;
                }
                /*TODO filter city if country = singapore or monaco*/
                if ($skip_country == "singapore" || $skip_country == "monaco") {
                    $shipping_address['city'] = str_ireplace($skip_country, '', $shipping_address['city']);
                    break;
                }
            }
            $shipping_address['country'] = str_ireplace($address_shipping_countryskip, '', $shipping_address['country']);
        }


        $address_format_set = str_replace(array("\n", '<br />', '<br/>', "\r"), '', $this->getAddressFormat());
        if (strpos($order->getData('shipping_method'),'storepickup') !== false){
            $address_format_set = '{if name}{name},|{/if}';
        }
        $capitalize_label_yn = 0;
        $address_format_set = $this->getArrayShippingAddress($shipping_address, $capitalize_label_yn, $address_format_set);

        $shippingAddressArray = $this->wordWrapOnRealFont($address_format_set, $this->addressLabelConfig['font_family_label'], $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'], $this->label_width);

        return $shippingAddressArray;
    }

    public function prepareShippingAddres(){
        $order = $this->getOrder();

        $has_shipping_address = false;

        foreach ($order->getAddressesCollection() as $address) {
            if ($address->getAddressType() == 'shipping' && !$address->isDeleted()) {
                $has_shipping_address = true;
                break;
            }
        }

        if ($has_shipping_address == false)
            return array();

        if ($has_shipping_address) {
            if ($order->getShippingAddress()->getFax())
                $customer_fax = trim($order->getShippingAddress()->getFax());
            else
                $customer_fax = '';

            if ($order->getShippingAddress()->getTelephone())
                $customer_phone = trim($order->getShippingAddress()->getTelephone());
            else
                $customer_phone = '';

            if ($order->getCustomerEmail())
                $customer_email = trim($order->getCustomerEmail());
            else
                $customer_email = '';

            if ($order->getShippingAddress()->getCompany())
                $customer_company = trim($order->getShippingAddress()->getCompany());
            else
                $customer_company = '';

            if ($order->getShippingAddress()->getName())
                $customer_name = trim($order->getShippingAddress()->getName());
            else
                $customer_name = '';

            if ($order->getShippingAddress()->getFirstname())
                $customer_firstname = trim($order->getShippingAddress()->getFirstname());
            else
                $customer_firstname = '';

            if ($order->getShippingAddress()->getLastname())
                $customer_lastname = trim($order->getShippingAddress()->getLastname());
            else
                $customer_lastname = '';

            if ($order->getShippingAddress()->getCity())
                $customer_city = trim($order->getShippingAddress()->getCity());
            else
                $customer_city = '';

            if ($order->getShippingAddress()->getPostcode())
                $customer_postcode = trim(strtoupper($order->getShippingAddress()->getPostcode()));
            else
                $customer_postcode = '';

            if ($order->getShippingAddress()->getRegion())
                $customer_region = trim($order->getShippingAddress()->getRegion());
            else
                $customer_region = '';

            if ($order->getShippingAddress()->getRegionCode())
                $customer_region_code = trim($order->getShippingAddress()->getRegionCode());
            else
                $customer_region_code = '';

            if ($order->getShippingAddress()->getPrefix())
                $customer_prefix = trim($order->getShippingAddress()->getPrefix());
            else
                $customer_prefix = '';

            if ($order->getShippingAddress()->getSuffix())
                $customer_suffix = trim($order->getShippingAddress()->getSuffix());
            else
                $customer_suffix = '';

            if ($order->getShippingAddress()->getStreet1())
                $customer_street1 = trim($order->getShippingAddress()->getStreet1());
            else
                $customer_street1 = '';

            if ($order->getShippingAddress()->getStreet2())
                $customer_street2 = trim($order->getShippingAddress()->getStreet2());
            else
                $customer_street2 = '';

            if ($order->getShippingAddress()->getStreet3())
                $customer_street3 = trim($order->getShippingAddress()->getStreet3());
            else
                $customer_street3 = '';

            if ($order->getShippingAddress()->getStreet4())
                $customer_street4 = trim($order->getShippingAddress()->getStreet4());
            else
                $customer_street4 = '';

            if ($order->getShippingAddress()->getStreet5())
                $customer_street5 = trim($order->getShippingAddress()->getStreet5());
            else
                $customer_street5 = '';

            if (Mage::app()->getLocale()->getCountryTranslation($order->getShippingAddress()->getCountryId())) {
                $customer_country = trim(Mage::app()->getLocale()->getCountryTranslation($order->getShippingAddress()->getCountryId()));
            } else
                $customer_country = '';
        }

        $shipping_address = array();
        $if_contents = array();
        $shipping_address['company'] = $customer_company;
        $shipping_address['firstname'] = ucwords($customer_firstname);
        $shipping_address['lastname'] = ucwords($customer_lastname);
        $shipping_address['name'] = $customer_name;
        $shipping_address['name'] = ucwords(trim(preg_replace('~^' . $shipping_address['company'] . '~i', '', $shipping_address['name'])));
        $shipping_address['city'] = ucwords($customer_city);
        $shipping_address['postcode'] = strtoupper($customer_postcode);
        $shipping_address['region_full'] = ucwords($customer_region);
        $shipping_address['region_code'] = strtoupper($customer_region_code);
        if ($customer_region_code != '') {
            $shipping_address['region'] = $customer_region_code;
        } else {
            $shipping_address['region'] = $customer_region;
        }
        $shipping_address['prefix'] = $customer_prefix;
        $shipping_address['suffix'] = $customer_suffix;
        $shipping_address['country'] = $customer_country;
        $shipping_address['street'] = ucwords($customer_street1);
        $shipping_address['street1'] = ucwords($customer_street1);
        $shipping_address['street2'] = ucwords($customer_street2);
        $shipping_address['street3'] = ucwords($customer_street3);
        $shipping_address['street4'] = ucwords($customer_street4);
        $shipping_address['street5'] = ucwords($customer_street5);


        return $shipping_address;
    }

    public function getAddressFormat() {
        $override_address_format_yn = $this->generalConfig['override_address_format_yn'];
        $custom_address_format = $this->generalConfig['address_format'];
        $default_address_format = Mage::getStoreConfig('customer/address_templates/pdf');
        $default_address_format = str_replace(array("depend", 'var ', '{{', '}}'), array("if", '', '{', '}'), $default_address_format);
        if ($override_address_format_yn == 1)
            $addressFormat = $custom_address_format;
        else
            $addressFormat = $default_address_format;

        return $addressFormat;
    }

    protected function getArrayShippingAddress($shipping_address, $capitalize_label_yn, $address_format_set) {
        $if_contents = array();

        foreach ($shipping_address as $key => $value) {
            $value = trim($value);
            if (($capitalize_label_yn == 1) && ($key != 'postcode') && ($key != 'region_code') && ($key != 'region'))
                $value = $this->reformatAddress($value,'uppercase');
            elseif ( ($capitalize_label_yn == 2)
                || ($key == 'postcode') || ($key == 'region_code') )
                $value = $this->reformatAddress($value,'capitals');

            $value = str_replace(array(',,', ', ,', ', ,'), ',', $value);
            $value = str_replace(array('N/a', 'n/a', 'N/A'), '', $value);
            $value = trim(preg_replace('~\-$~', '', $value));

            // all-caps in address-format string only for COUNTRY and REGION
            if (($key == "region") || ($key == "country")){
                $str = preg_replace("/{\/if}(.*)/", "", $address_format_set);
                $str = preg_replace("/({if ".$key."}|\|)/", "", $str);
                $str = preg_replace("/((.*)\{|\}(.*))/", "", $str);
                if ($this->reformatAddress($key,'capitals') == $str)
                    $value = $this->reformatAddress($value,'capitals');
            }

            //check key in format address string
            $string_key_check = '{if ' . $key . '}';
            $key_flag = strpos($address_format_set, $string_key_check);
            $search = array($string_key_check, '{/if}');
            $replace = array('', '');
            if ($key_flag !== FALSE)
                $address_format_set = str_replace($search, $replace, $address_format_set);
            // end check key in format address string

            if ($value != '' && !is_array($value)) {
                $pre_value = '';
                preg_match('~\{if ' . $key . '\}(.*)\{\/if ' . $key . '\}~ims', $address_format_set, $if_contents);

                if (isset($if_contents[1]))
                    $if_contents[1] = str_replace('{' . $key . '}', $value, $if_contents[1]);
                else
                    $if_contents[1] = '';

                $address_format_set = preg_replace('~\{if ' . $key . '\}(.*)\{/if ' . $key . '\}~ims', $if_contents[1], $address_format_set);
                $address_format_set = str_ireplace('{' . $key . '}', $pre_value . $value, $address_format_set);
                $address_format_set = str_ireplace('{/' . $key . '}', '', $address_format_set);
                $address_format_set = str_ireplace('{/if ' . $key . '}', '', $address_format_set);
                $address_format_set = str_ireplace('{/if ' . '}', '', $address_format_set);
            } else {
                $pre_value = '';
                $address_format_set = preg_replace('~\{if ' . $key . '\}(.*)\{/if ' . $key . '\}~i', '', $address_format_set);
                $address_format_set = str_replace('{' . $key . '}', '', $address_format_set);
                $address_format_set = str_ireplace('{' . $key . '}', $pre_value . $value, $address_format_set);
                $address_format_set = str_ireplace('{/' . $key . '}', '', $address_format_set);
                $address_format_set = str_ireplace('{/if ' . $key . '}', '', $address_format_set);
                $address_format_set = str_ireplace('{/if ' . '}', '', $address_format_set);
                //$address_format_set = str_ireplace(', ', '', $address_format_set);
            }

            $from_date = "{if telephone}";
            $end_date = "{telephone}";
            $from_date_pos = strpos($address_format_set, $from_date);
            if ($from_date_pos !== false) {
                $end_date_pos = strpos($address_format_set, $end_date) + strlen($end_date);
                $date_length = $end_date_pos - $from_date_pos;
                $date_str = substr($address_format_set, $from_date_pos, $date_length);
                $address_format_set = str_replace($date_str, '', $address_format_set);
            }

            $from_date = "{if fax}";
            $end_date = "{fax}";
            $from_date_pos = strpos($address_format_set, $from_date);
            if ($from_date_pos !== false) {
                $end_date_pos = strpos($address_format_set, $end_date) + strlen($end_date);
                $date_length = $end_date_pos - $from_date_pos;
                $date_str = substr($address_format_set, $from_date_pos, $date_length);
                $address_format_set = str_replace($date_str, '', $address_format_set);
            }

            $from_date = "{if vat_id}";
            $end_date = "{vat_id}";
            $from_date_pos = strpos($address_format_set, $from_date);
            if ($from_date_pos !== false) {
                $end_date_pos = strpos($address_format_set, $end_date) + strlen($end_date);
                $date_length = $end_date_pos - $from_date_pos;
                $date_str = substr($address_format_set, $from_date_pos, $date_length);
                $address_format_set = str_replace($date_str, '', $address_format_set);
            }
        }
        $address_format_set = trim(str_replace(array('||', '|'), "\n", trim($address_format_set)));
        $address_format_set = str_replace("\n\n", "\n", $address_format_set);
        $address_format_set = str_replace("  ", " ", $address_format_set);
        $address_format_set = trim(ltrim($address_format_set,','));

        return $address_format_set;
    }

    protected function reformatAddress($str, $change_to = 'none') {
        switch ($change_to) {
            case 'capitals':
                return $this->capitalAddress($str);
                break;

            case 'uppercase':
                return $this->uppercaseAddress($str);
                break;

            case 'none':
            default:
                return $this->prepareText($str);
                break;
        }
    }

    private function capitalAddress($str) {
        $str = $this->prepareText($str);
        $str = mb_convert_case($str, MB_CASE_UPPER, 'UTF-8');
        return strtr($str, array("ß" => "SS"));
    }

    private function removeAccentsFromChars($str) {
        // Depending on font chosen, change characters
        if($this->generalConfig['remove_accents_from_chars_yn'] == 0)
            return $str;
        else
            return Mage::helper('pickpack/functions')->normalizeChars($str);
    }

    private function fixProblemCharacters($str) {
        // Depending on font chosen, change characters
        if($this->generalConfig['font_family_body'] == 'noto')
            return $str;
        else
            return str_replace($this->_charactedMap['from'], $this->_charactedMap['to'], $str);
    }

    private function prepareText($str) {
        $str = $this->fixProblemCharacters($str);
        $str = $this->removeAccentsFromChars($str);
        return $str;
    }

    public function printZipcodeBarcode(){
        $order = $this->getOrder();
        $black_color = Mage::helper('pickpack/config_color')->getPdfColor('black_color');

        if ($order->getShippingAddress() && $order->getShippingAddress()->getPostcode()) {
            $zipcode = trim(strtoupper($order->getShippingAddress()->getPostcode()));
        }

        if ($zipcode != ''){
            //check to create new label block
            $barcode_font_size = 26;
            $this->y -= 1.4 * $barcode_font_size;
            if ($this->y < $this->current_label_bottom){
                $this->newLabelBlock();
                $this->y -= 1.4 * $barcode_font_size;
            }
            $page = $this->getPage();

            $barcodeString = Mage::helper('pickpack/barcode')->convertToBarcodeString($zipcode, $this->generalConfig['barcode_type']);
            $page->setFillColor($black_color);
            $page->setLineColor($black_color);
            $page->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0));
            $page->setFont(Zend_Pdf_Font::fontWithPath($this->action_path . $this->generalConfig['font_family_barcode']), $barcode_font_size);
            $this->order_barcode_x = $this->current_label_left + $this->addressLabelConfig['nudge_order_id_barcode'][0];
            $this->order_barcode_y = $this->y + $this->addressLabelConfig['nudge_order_id_barcode'][1];
            $page->drawText($barcodeString, $this->order_barcode_x, $this->order_barcode_y, 'CP1252');

            //return normal
            $page->setFillColor($black_color);
            Mage::helper('pickpack/font')->setFontRegular($page, $this->generalConfig['font_size_body']);

            $this->y -= 1.5 * $this->generalConfig['font_size_body'];
        }

    }

    public function printProductList(){
        $page = $this->getPage();
        $order = $this->getOrder();
        $helper = Mage::helper('pickpack');

        $product_list = $this->getProductListData();

        //word wrap product list in case we have long product name
        $print_list = array();
        foreach ($product_list as $product){
            $text = $product['qty'].' X '.$product['name'];

            $line_array = $this->wordWrapOnRealFont($text, $this->addressLabelConfig['font_family_label'], $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'], $this->label_width);
            $print_list = array_merge($print_list, $line_array);
        }

        $this->y -= 1.2 * $this->addressLabelConfig['font_size_label'];

        //check if need to create new label block
        $min_space_need_for_first_product_item = 1.2 * ($this->addressLabelConfig['font_size_label'] - 2) + 1.2 * ($this->addressLabelConfig['font_size_label'] - 3);
        if ($this->y - $min_space_need_for_first_product_item < $this->current_label_bottom){
            $this->newLabelBlock();
            $this->y -= 1.2 * $this->addressLabelConfig['font_size_label'];
            $page = $this->getPage();
        }

        $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'], $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
        $order_id = $order->getRealOrderId();
        $page->drawText("#".$order_id, $this->current_label_left, $this->y, 'UTF-8');
        $this->y -= 1.2 * ($this->addressLabelConfig['font_size_label'] - 2);
        $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'] - 2, $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
        $page->drawText($helper->__('Your Items:'), $this->current_label_left, $this->y, 'UTF-8');
        $this->y -= 1.2 * ($this->addressLabelConfig['font_size_label'] - 3);

        //print each line of product list block
        $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'] - 3, $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
        foreach ($print_list as $line){
            //check if need to create new label block
            if ($this->y < $this->current_label_bottom){
                $this->newLabelBlock();
                $this->y -= 1.2 * ($this->addressLabelConfig['font_size_label'] - 3);
                $page = $this->getPage();
                $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'] - 3, $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
            }

            $page->drawText($line, $this->current_label_left, $this->y, 'UTF-8');
            $this->y -= 1.2 * ($this->addressLabelConfig['font_size_label'] - 3);
        }

    }

    public function getProductListData(){
        $order = $this->getOrder();

        $itemsCollection = Mage::helper('pickpack/order')->getItemsToProcess($order);

        $product_list = array();
        $count_product = 0;
        $exist_product_id = array(); // this array use to list all product was put to product list, to be sure there no duplicate

        foreach ($itemsCollection as $item){
            $product_id = $item->getData('product_id');
            if (!isset($exist_product_id[$product_id])){
                $exist_product_id[$product_id] = $count_product;

                $product_list[] = array(
                    'qty' => intval($item->getData('qty_ordered')),
                    'name' => $item->getData('name')
                );

                $count_product ++;
            }else{
                $key = $exist_product_id[$product_id];
                $product_list[$key]['qty'] += intval($item->getData('qty_ordered'));
            }
        }

        return $product_list;
    }

    public function printReturnAddressOnNewLabel(){
        $helper = Mage::helper('pickpack');

        $this->newLabelBlock();
        $this->y -= 1.2 * $this->addressLabelConfig['font_size_return_label'];

        $page = $this->getPage();

        $return_address = $this->addressLabelConfig['label_return_address'];

        $return_address_array = $this->wordWrapOnRealFont($return_address, $this->addressLabelConfig['font_family_return_label'], $this->addressLabelConfig['font_style_return_label'], $this->addressLabelConfig['font_size_return_label'], $this->addressLabelConfig['label_width']);

        $this->_setFont($page, $this->addressLabelConfig['font_style_return_label'], $this->addressLabelConfig['font_size_return_label'], $this->addressLabelConfig['font_family_return_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_return_label']);

        $page->drawText($helper->__('From') . ' :', $this->current_label_left, $this->y, 'UTF-8');
        $this->y -= 1.2 * $this->addressLabelConfig['font_size_return_label'];
        foreach ($return_address_array as $return_address_line){
            //check if need to create new label block
            if ($this->y < $this->current_label_bottom){
                $this->newLabelBlock();
                $this->y -= 1.2 * $this->addressLabelConfig['font_size_return_label'];
                $page = $this->getPage();
                $this->_setFont($page, $this->addressLabelConfig['font_style_label'], $this->addressLabelConfig['font_size_label'] - 3, $this->addressLabelConfig['font_family_label'], $this->generalConfig['non_standard_characters'], $this->addressLabelConfig['font_color_label']);
            }

            $page->drawText($return_address_line, $this->current_label_left, $this->y, 'UTF-8');
            $this->y -= 1.2 * $this->addressLabelConfig['font_size_return_label'];
        }

    }

    public function caculateMaxCharacter($line_string, $font_size, $font_temp, $max_width) {
        $line_width_message = $this->parseString($line_string, $font_temp, $font_size);
        $char_width_message = ceil($line_width_message / strlen($line_string));
        $max_chars_message = floor($max_width / $char_width_message);
        return $max_chars_message;
    }

    private function wordWrapOnRealFont($text, $font_family, $font_style, $font_size, $max_width){
        $string_array = array();
        $font_temp = Mage::helper('pickpack/font')->getFontName2($font_family, $font_style, $this->generalConfig['non_standard_characters']);

        $string_line_array = preg_split ('/$\R?^/m', $text);
        foreach ($string_line_array as $string_line){
            if ($string_line != "") {
                $string_line = trim($string_line);
                $character_breakpoint = $this->caculateMaxCharacter($string_line, $font_size, $font_temp, $max_width);
                $string_line_wraped_array = mb_wordwrap_array($string_line, $character_breakpoint);
                $string_array = array_merge($string_array, $string_line_wraped_array);
            }
        }
        return $string_array;
    }

    public function printCourierRulesLabel($orderSingle) {
        try {
            if (mageFindClassFile('Moogento_CourierRules_Helper_Connector')) {
                $labels = Mage::helper('moogento_courierrules/connector')->getConnectorLabels($orderSingle);
                if (count($labels)) {
                    foreach ($labels as $label) {
                        //create new block for each image
                        $this->newLabelBlock();
                        $page = $this->getPage();

                        $tmpFile = Mage::helper('pickpack')->getConnectorLabelTmpFile($label);
                        $imageObj = Zend_Pdf_Image::imageWithPath($tmpFile);
                        $page->drawImage($imageObj, $this->current_label_left, $this->current_label_bottom, $this->current_label_right, $this->current_label_top);
                        unset($tmpFile);
                    }
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function printShippingLabelImage() {
        $image_name = Mage::getStoreConfig('pickpack_options/label/label_logo', $this->getCurrentStoreId());
        if(!is_null($image_name)){
            $filename = Mage::getBaseDir('media') . '/moogento/pickpack/logo_label/' . $image_name;
            $temp_array_image = explode('.', $image_name);
            $image_ext = strtolower(array_pop($temp_array_image));
                if ((($image_ext == 'jpg') || ($image_ext == 'jpeg') || ($image_ext == 'png')) && (is_file($filename))) {
                    $top_background_scale = ($this->addressLabelConfig['top_shipping_address_background_yn_scale'])?
                        $this->addressLabelConfig['top_shipping_address_background_yn_scale']/100: 1;
                    $label_nudgelogo_X = $this->addressLabelConfig['label_nudgelogo'][0];
                    $label_nudgelogo_Y = $this->addressLabelConfig['label_nudgelogo'][1];

                    $imageObj = Mage::helper('pickpack')->getImageObj($filename);

                    $orig_img_width  = $imageObj->getOriginalWidth();
                    $orig_img_height = $imageObj->getOriginalHeight();

                    $width = ($this->getLabelWidth() - $label_nudgelogo_X) * $top_background_scale;
                    $height = ($this->getLabelHeight() - $label_nudgelogo_Y) * $top_background_scale;

                    if ($orig_img_width > $width) {
                        $img_width  = $width;
                        $img_height = ceil(($img_width / ($orig_img_width)) * $orig_img_height);
                    } elseif ($orig_img_height > $height) {
                        $img_height  = $height;
                        $img_width = ceil(($img_height / ($orig_img_height)) * $orig_img_width);
                    }
                    $image = Zend_Pdf_Image::imageWithPath($filename);

                    $this->getPage()->drawImage($image, $this->getCurrentLabelLeft() + $label_nudgelogo_X, $this->getCurrentLabelTop() - $label_nudgelogo_Y - $img_height,
                        $this->getCurrentLabelLeft() + $label_nudgelogo_X + $img_width, $this->getCurrentLabelTop() - $label_nudgelogo_Y);
                    $this->y = $this->getCurrentLabelTop() - $label_nudgelogo_Y - $img_height * 0.3;
                }
        }
    }
}