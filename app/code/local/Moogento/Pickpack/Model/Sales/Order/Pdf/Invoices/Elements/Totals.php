<?php

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Totals extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Abstract
{
    public $total_data = array(); // this object is use to store collect data when pickpack run throught products.php
    protected $total_print = array(); // this object is use to store caculate total array to print

    public function showTotals(){
        $storeId = $this->getStoreId();
        $order = $this->getOrder();

        $prices_yn = $this->isShowPrices();
        if ($prices_yn != 0){
            $this->orderCurrencyCode = $order->getOrderCurrencyCode();
            $this->showBaseValue = false;
            if ($this->packingsheetConfig['total_base_prices_yn']){
                $this->baseCurrencyCode = Mage::app()->getStore($storeId)->getBaseCurrencyCode();
                if ($this->orderCurrencyCode != $this->baseCurrencyCode){
                    $this->base_currency_font_color = Mage::helper('pickpack/config_color')->adjustBrightness($this->generalConfig['font_color_body'], 100);
                    $this->showBaseValue = true;
                }
            }

            $this->calculateTotalsArray();
            $this->sortTotalsArray();
            $this->checkNewPage();
            $this->printTotalArray();
        }
    }

    private function checkNewPage(){
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $pageConfig = $this->getPageConfig();
        $page = $this->getPage();

        $total_block_height = 0;
        $has_sub_info_line = false;

        foreach ($this->total_print as $key => $item_print){
            if ($key == 'grand_total'){
                $total_block_height += 1.2 * $this->generalConfig['font_size_body'];
            }
            if ($key == 'sub_info' && count($item_print) && $has_sub_info_line == false){
                $total_block_height += 1.2 * $this->generalConfig['font_size_body'];
                $has_sub_info_line = true;
            }
            foreach ($item_print as $row){
                $total_block_height += 1.5 * $this->generalConfig['font_size_body'];
            }
        }

        /**CHECK NEED TO CREATE NEW PAGE BEFORE PRINTING TOTALS OR NOT**/
        // new logic for custom image after product list and total
        if ($this->y - $total_block_height < $this->minYTotal) {
            $this->newPage();
            if ($this->generalConfig['second_page_start'] == 'asfirst')
                $this->y = $this->items_header_top_firstpage;
            else
                $this->y = $pageConfig['page_top'];
            
            $this->drawPageNumber();

            $this->y = ($this->y - ($this->generalConfig['font_size_body'] * 2));
        }
    }

    private function drawPageNumber(){
        $helper = Mage::helper('pickpack');
        $page = $this->getPage();
        $pageConfig = $this->getPageConfig();
        $generalConfig = $this->getGeneralConfig();

        $this->_setFont($page, 'semibold', $generalConfig['font_size_body'], $generalConfig['font_family_body'], $generalConfig['non_standard_characters'], $generalConfig['font_color_body']);
        if($this->countPdfPages()) {
            $paging_text = '-- ' . $helper->__('Page') . ' ' . $this->countPdfPages() . ' --';
            $paging_text_width = $this->widthForStringUsingFontSize($paging_text, $generalConfig['font_family_body'], ($generalConfig['font_size_body'] - 2), $generalConfig['font_style_body'], $generalConfig['non_standard_characters']);
            $paging_text_x = (($pageConfig['padded_right'] / 2) - ($paging_text_width / 2));
            $this->_drawText($paging_text, $paging_text_x, ($this->y));
            $this->y -= ($generalConfig['font_size_body'] * 1.7);

            return true;
        }

        return false;
    }

    private function countPdfPages(){
        $page = $this->getPage();

        $page_count = ($this->getPdf()->_PdfPageCount + 1);
        if( isset($page_count) && ($page_count > 1) )
            return $page_count;
        else return false;
    }

    private function calculateTotalsArray(){
        $order = $this->getOrder();

        $this->total_print['subtotal'] = array();
        $this->total_print['discount'] = array();
        $this->total_print['tax'] = array();
        $this->total_print['shipping'] = array();
        $this->total_print['custom_fee'] = array();
        $this->total_print['refunds'] = array();
        $this->total_print['grand_total'] = array();
        $this->total_print['sub_info'] = array();

        //get discount first
        $discountModel = Mage::getModel('pickpack/sales_order_pdf_invoices_elements_totals_discount', array($this, $order));
        $discountModel->generalConfig = $this->generalConfig;
        $discountModel->packingsheetConfig = $this->packingsheetConfig;
        $this->total_print['discount'] = $discountModel->caculateDiscount($this->total_data);
        //get tax
        $taxModel = Mage::getModel('pickpack/sales_order_pdf_invoices_elements_totals_tax', array($this, $order));
        $taxModel->generalConfig = $this->generalConfig;
        $taxModel->packingsheetConfig = $this->packingsheetConfig;
        $this->total_print['tax'] = $taxModel->calculateTax($this->total_data);
        //get shipping
        $shippingModel = Mage::getModel('pickpack/sales_order_pdf_invoices_elements_totals_shipping', array($this, $order));
        $shippingModel->generalConfig = $this->generalConfig;
        $shippingModel->packingsheetConfig = $this->packingsheetConfig;
        $this->total_print['shipping'] = $shippingModel->caculateShipping($this->total_data);
        //get custom fee
        $customFeeModel = Mage::getModel('pickpack/sales_order_pdf_invoices_elements_totals_customfee', array($this, $order));
        $customFeeModel->generalConfig = $this->generalConfig;
        $customFeeModel->packingsheetConfig = $this->packingsheetConfig;
        $this->total_print['custom_fee'] = $customFeeModel->caculateCustomFee($this->total_data);
        //get subtotals
        $subtotalsModel = Mage::getModel('pickpack/sales_order_pdf_invoices_elements_totals_subtotals', array($this, $order));
        $subtotalsModel->generalConfig = $this->generalConfig;
        $subtotalsModel->packingsheetConfig = $this->packingsheetConfig;
        $this->total_print['subtotal'] = $subtotalsModel->caculateSubtotals($this->total_data);

        // if customer using magento grandtotal value then total_refunded will print like subinfog and will not add to grand_total value
        if ($this->packingsheetConfig['total_default_grandtotal_yn'] == 0){
            //get refunds
            $refundsModel = Mage::getModel('pickpack/sales_order_pdf_invoices_elements_totals_refunds', array($this, $order));
            $refundsModel->generalConfig = $this->generalConfig;
            $refundsModel->packingsheetConfig = $this->packingsheetConfig;
            $this->total_print['refunds'] = $refundsModel->caculateRefunds($this->total_data);
        }

        //get grandtotal
        $grandtotalModel = Mage::getModel('pickpack/sales_order_pdf_invoices_elements_totals_grandtotal', array($this, $order));
        $grandtotalModel->generalConfig = $this->generalConfig;
        $grandtotalModel->packingsheetConfig = $this->packingsheetConfig;
        $this->total_print['grand_total'] = $grandtotalModel->caculateGrandTotal($this->total_data);

        $this->total_print['sub_info'] = 'sub_info';

        $subinfoModel = Mage::getModel('pickpack/sales_order_pdf_invoices_elements_totals_subinfo', array($this, $order));
        $subinfoModel->generalConfig = $this->generalConfig;
        $subinfoModel->packingsheetConfig = $this->packingsheetConfig;
        $this->total_print['sub_info'] = $subinfoModel->getSubInfo($this->total_data);
    }

    private function sortTotalsArray(){
        // this will sort $total_print array base on subtotal_order config
        $sort_array = $this->packingsheetConfig['subtotal_order'];

        //add 3 place for custom_fee, grand_total and sub_info
        $max = max($sort_array);
		
		while(count($sort_array) < count($this->total_print)) {
			$max += 1;
			$sort_array[] = strval($max);
		}

        array_multisort($sort_array, $this->total_print);
    }

    private function printTotalArray(){
        //in this function we will print $total_print array base on setting font in pickpack configuration
        $order = $this->getOrder();
        $wonder = $this->getWonder();
        $helper = Mage::helper('pickpack');
        $storeId = $this->getStoreId();
        $pageConfig = $this->getPageConfig();
        $page = $this->getPage();

        $packingsheetConfig = $this->getPackingsheetConfig($wonder,$storeId);

        $subtotal_align = $this->_getConfig('subtotal_align', 1, false, $wonder, $storeId);

		// override setting and align to total column
		$subtotal_align_pos = array();
		$subtotal_align_pos[1] = $packingsheetConfig['product_line_total_title_xpos'];
		$subtotal_align_pos[0] = $packingsheetConfig['product_line_total_title_xpos'] - ( $this->generalConfig['font_size_body'] * strlen($helper->__('Grand Total') * 1) );
		if (strtoupper($this->generalConfig['background_color_subtitles']) != '#FFFFFF') {
			$background_color_subtitles = new Zend_Pdf_Color_Html($this->generalConfig['background_color_subtitles']);
	        $page->setLineColor($background_color_subtitles);
	        $page->setLineWidth(0.5);
			$page->drawLine($pageConfig['padded_left'], ($this->y + 1.7* $this->generalConfig['font_size_body']), $pageConfig['padded_right'], ($this->y + 1.7* $this->generalConfig['font_size_body']));
		}
		
        //set font for total line before print
        $this->_setFont($page, 'regular', $this->generalConfig['font_size_body'], $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->generalConfig['font_color_body']);

        $has_sub_info_line = false;
        foreach ($this->total_print as $key => $item_print){
           
		    if ($key == 'grand_total'){				
                $page->drawLine($subtotal_align_pos[0] - 100, ($this->y + 0.5* $this->generalConfig['font_size_body']), $pageConfig['padded_right'], ($this->y + 0.5* $this->generalConfig['font_size_body']));
                $this->y -= 1.2 * $this->generalConfig['font_size_body'];
            }

            if ($key == 'sub_info' && count($item_print) && $has_sub_info_line == false){
                $page->drawLine($subtotal_align_pos[0] - 100, ($this->y + 0.5* $this->generalConfig['font_size_body']), $pageConfig['padded_right'], ($this->y + 0.5* $this->generalConfig['font_size_body']));
                $this->y -= 1.2 * $this->generalConfig['font_size_body'];
                $has_sub_info_line = true;
            }
            foreach ($item_print as $row){
                $text = $row['text'];
                //$value = $this->formatPriceTxt($order, number_format(floatval($row['value']), 2, '.', ','));
                
                if ($subtotal_align == 1) {
                    $subtotal_label_rightalign_xpos = $subtotal_align_pos[0];
                    $subtotal_label_xpos = $this->rightAlign2($text, $this->generalConfig['font_family_body'], $this->generalConfig['font_size_body'], 'regular', $subtotal_label_rightalign_xpos);
                } else 
					$subtotal_label_xpos = $subtotal_align_pos[0];

			    if ($key == 'grand_total')
			        $this->_setFont($page, 'semibold', $this->generalConfig['font_size_body'], $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->generalConfig['font_color_body']);

				$was_include_left = '';
				$was_include_right = '';
                $was_include_both = '';
				if ( (($row['key'] == "tax")||($row['key'] == "discount")) && ($row['incl'] === true) ) {
					$was_include_left = 'left';
					$was_include_right = 'right';
                    if (isset($row['base_value'])){
                        $was_include_both = 'both';
                    }
				}
                $this->printValueLineSubtotal($text, $subtotal_label_xpos, $this->y, 'label', $was_include_left);

				if ($key == 'grand_total')
					$this->_setFont($page, 'regular', $this->generalConfig['font_size_body'], $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->generalConfig['font_color_body']);

				$this->printValueLineSubtotal($row['value'], $subtotal_align_pos[1], $this->y, 'value', $was_include_right, 'current');

                //Need to re-calculate this->y. If print in multiline coupon code.
                $this->y -= 1.5 * $this->generalConfig['font_size_body'];

                if ($this->showBaseValue){
                    if (isset($row['base_value'])){
                        $this->_setFont($page, 'italic', $this->generalConfig['font_size_body'], $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->base_currency_font_color);
                        $this->printValueLineSubtotal($row['base_value'], $subtotal_align_pos[1], $this->y, 'value', $was_include_both, 'base');
                        $this->_setFont($page, 'regular', $this->generalConfig['font_size_body'], $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->generalConfig['font_color_body']);
                        //Need to re-calculate this->y. If print in multiline coupon code.
                        $this->y -= 1.5 * $this->generalConfig['font_size_body'];
                    }
                }

            }
        }
    }
	
    private function printValueLineSubtotal($print_value, $x, $y, $label_or_value='label', $was_include = false, $currency_code = 'current'){
        
	    if (!is_null($print_value)){
	        $page = $this->getPage();
	        $order = $this->getOrder();

            if ($currency_code == 'current')
                $currency_code = $this->orderCurrencyCode;
            elseif ($currency_code == 'base')
                $currency_code = $this->baseCurrencyCode;

			$print_value_display = $print_value;
			
			if(isset($label_or_value) && ($label_or_value == 'value')) {
				$print_value_display = Mage::getModel('directory/currency')->setData('currency_code', $currency_code)
						->format($print_value, array('display' =>Zend_Currency::NO_SYMBOL), false);

                //missing this setting in system.xml
                //$this->packingsheetConfig['currency_codes_or_symbols'];

				if(($this->packingsheetConfig['currency_codes_or_symbols'] == 'codes') 
				|| ($this->packingsheetConfig['currency_codes_or_symbols'] == 'symbols') 
				|| ($this->packingsheetConfig['currency_codes_or_symbols'] == 'both')) {
					$order_currency_code = Mage::app()->getLocale()->currency($currency_code)->getShortname();
					$print_symbol = '';
				
					if($this->packingsheetConfig['currency_codes_or_symbols'] == 'codes')
						$print_symbol = $order_currency_code;
					elseif($this->packingsheetConfig['currency_codes_or_symbols'] == 'symbols')
						$print_symbol = Mage::app()->getLocale()->currency($currency_code)->getSymbol();
					elseif($this->packingsheetConfig['currency_codes_or_symbols'] == 'both')
						$print_symbol = $order_currency_code.Mage::app()->getLocale()->currency($currency_code)->getSymbol();

					switch ($this->packingsheetConfig['currency_symbol_position']) {
						case 'right':
							$print_value_display = $print_value_display.$print_symbol;
							break;

						case 'auto':
							switch ($order_currency_code) {

								case 'EUR':
									$print_value_display = $print_value_display.$print_symbol;
									break;
					
								case 'USD':
								case 'CAD':
								case 'GBP':		
								default:
									$print_value_display = $print_symbol.$print_value_display;
									break;
							}
							break;
							
						case 'left':
						case 'magento':
						default:
							$print_value_display = $print_symbol.$print_value_display;
							break;
					}
				}
			  	
		  	  	// may fix locale specific currency placement
				if($this->packingsheetConfig['currency_codes_or_symbols'] == 'magento')
                    $print_value_display = Mage::getModel('directory/currency')->setData('currency_code', $currency_code)
                        ->format($print_value, array('display' =>Zend_Currency::USE_SYMBOL), false);
			}

			if (isset($was_include) && ($was_include == 'left'))
                $print_value_display = '('.$print_value_display;	
			elseif (isset($was_include) && ($was_include == 'right'))
            	$print_value_display = $print_value_display.')';
            elseif (isset($was_include) && ($was_include == 'both'))
                $print_value_display = '('.$print_value_display.')';
			
            $this->_drawText($print_value_display, $x, $y);
        }
    }

    protected function rightAlign2($str, $font_family, $font_size, $style = 'regular', $subtotal_label_rightalign_xpos) {
        //Real string, real font, real size, real style.
        $font_temp  = $this->getFontName2($font_family, $style);
        $line_width = $this->parseString($str, $font_temp, $font_size);
        return $subtotal_label_rightalign_xpos - $line_width;
    }

    public function getBaseCurrencyValue($current_currency_value){
        $base_currency_value = Mage::helper('directory')->currencyConvert($current_currency_value, $this->orderCurrencyCode, $this->baseCurrencyCode);
        return $base_currency_value;
    }
}