<?php
class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Products_Price extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Products
{
    protected $product_price = array();
    public $sub_total_data = array();

    public function prepareConfigObject(){
        $storeId = $this->getStoreId();
        $order = $this->getOrder();

        $this->orderCurrencyCode = $order->getOrderCurrencyCode();
        $this->showBaseValue = false;
        if ($this->packingsheetConfig['product_line_base_prices_yn']){
            $this->baseCurrencyCode = Mage::app()->getStore($storeId)->getBaseCurrencyCode();

            if ($this->baseCurrencyCode != $this->orderCurrencyCode){
                $this->base_currency_font_color = Mage::helper('pickpack/config_color')->adjustBrightness($this->generalConfig['font_color_body'], 100);
                $this->showBaseValue = true;
            }
        }
    }

    public function showProductPrice($product,$order_item){
        $order = $this->getOrder();
        $this->getProductPrice($product,$order_item);
        if ($this->showBaseValue)
            $this->getProductPrice($product,$order_item,'base_');
        //later we will have option to hide product price if product is gift
        $is_gift = $this->checkIsGiftProdduct($product,$order_item);
        // we should have one function in other class that scan all item in order to check if this is a gift order or not,
        // that function will run at start of pdf processing to check if we even need to add price to titlebar
        $this->printValueLine($this->product_price['print_price'],$this->packingsheetConfig['product_line_prices_title_xpos'],$this->y);
        if ($this->showBaseValue)
            $this->printValueLine($this->product_price['base_print_price'],$this->packingsheetConfig['product_line_prices_title_xpos'],$this->y - 1.5 * $this->generalConfig['font_size_body'], false, 'base_currency');
    }

    private function getProductPrice($product,$order_item, $base = ''){
        if($this->packingsheetConfig['filter_items_by_status'] == 1){
            $qty = floatval($order_item->getData('qty_invoiced'));
        }else{
            $qty = floatval($order_item->getData('qty_ordered'));
        }
        $tax_amount = floatval($order_item->getData($base.'tax_amount')) / $qty;
        $price_calculated = $order_item->getData($base.'price');
        $original_price = $order_item->getData($base.'original_price');
        if ($price_calculated < $original_price)
            $this->product_price[$base.'original_price'] = $price_calculated;
        else
            $this->product_price[$base.'original_price'] = $original_price;

        $this->product_price[$base.'print_price'] = null;
		//get value from magento order_item
        if ($this->packingsheetConfig['product_line_prices_yn'] == 1)
            $this->product_price[$base.'print_price'] = $order_item->getData($base.'price');
        elseif ($this->packingsheetConfig['product_line_prices_yn'] == 2){
            $tax_amount = 0;
            $discount_amount = 0;

			//get value tax
            if ($this->packingsheetConfig['product_line_prices_with_tax_yn'] == 1)
                $tax_amount = floatval($order_item->getData($base.'tax_amount')) / $qty;

            //get value discount
            if ($this->packingsheetConfig['product_line_prices_with_discount_yn'] == 1)
                $discount_amount = floatval($order_item->getData($base.'discount_amount')) / $qty;

            $this->product_price[$base.'print_price'] = $this->product_price[$base.'original_price'] - $discount_amount + $tax_amount;
        }
    }

    private function checkIsGiftProdduct($product,$order_item){
        return false;
    }

    public function showProductDiscount($product,$order_item){
        $this->getProductDiscount($product,$order_item);
        if ($this->showBaseValue)
            $this->getProductDiscount($product,$order_item,'base_');
        $discount_included = false;
        if ($this->packingsheetConfig['product_line_prices_with_discount_yn'] == 1)
			$discount_included = true;
        $this->printValueLine($this->product_price['print_discount'],$this->packingsheetConfig['product_line_discount_title_xpos'],$this->y,$discount_included);
        if ($this->showBaseValue)
            $this->printValueLine($this->product_price['base_print_discount'],$this->packingsheetConfig['product_line_discount_title_xpos'],$this->y - 1.5 * $this->generalConfig['font_size_body'], $discount_included, 'base_currency');
    }

    private function getProductDiscount($product,$order_item, $base = ''){
        if($this->packingsheetConfig['filter_items_by_status'] == 1){
            $qty = floatval($order_item->getData('qty_invoiced'));
        }else{
            $qty = floatval($order_item->getData('qty_ordered'));
        }
        $this->product_price[$base.'total_discount'] = -floatval($order_item->getData($base.'discount_amount'));
        $this->product_price[$base.'discount'] = $this->product_price[$base.'total_discount'] / $qty;
        $this->product_price[$base.'print_discount'] = null;
        if ($this->packingsheetConfig['product_line_discount_yn'] == 1){
            //get value from magento order_item
            $value = $this->product_price[$base.'discount'];
            if ($this->packingsheetConfig['hide_zero_discount_value'] == 0 || $value != 0)
                $this->product_price[$base.'print_discount'] = $value;
        }elseif ($this->packingsheetConfig['product_line_discount_yn'] == 2){
            //get tax from calculated value
            $value = $this->product_price[$base.'discount'];
            if ($this->packingsheetConfig['hide_zero_discount_value'] == 0 || $value != 0)
                $this->product_price[$base.'print_discount'] = $value;
        }
    }

    public function showProductTax($product,$order_item){
        $this->getProductTax($product,$order_item);
        if ($this->showBaseValue)
            $this->getProductTax($product,$order_item,'base_');
        $tax_included = false;
        if ($this->packingsheetConfig['product_line_prices_with_tax_yn'] == 1)
			$tax_included = true;
        $this->printValueLine($this->product_price['print_tax'],$this->packingsheetConfig['product_line_tax_title_xpos'],$this->y,$tax_included);
        if ($this->showBaseValue)
            $this->printValueLine($this->product_price['base_print_tax'],$this->packingsheetConfig['product_line_tax_title_xpos'],$this->y - 1.5 * $this->generalConfig['font_size_body'], $tax_included, 'base_currency');
    }

    private function getProductTax($product,$order_item, $base = ''){
        $only_invoiced = ($this->packingsheetConfig['filter_items_by_status'] == 1)? true: false;
        if($only_invoiced){
            $qty = floatval($order_item->getData('qty_invoiced'));
        }else{
            $qty = floatval($order_item->getData('qty_ordered'));
        }
        $this->product_price[$base.'total_tax'] = ($only_invoiced)? floatval($order_item->getData($base.'tax_invoiced')):floatval($order_item->getData($base.'tax_amount'));
        $this->product_price[$base.'tax'] = $this->product_price[$base.'total_tax'] / $qty;
        $this->product_price[$base.'print_tax'] = null;
        if ($this->packingsheetConfig['product_line_tax_yn'] == 1){
            //get value from magento order_item
            $value = $this->product_price[$base.'tax'];
            if ($this->packingsheetConfig['hide_zero_tax_value'] == 0 || $value != 0)
                $this->product_price[$base.'print_tax'] = $value;
        } elseif ($this->packingsheetConfig['product_line_tax_yn'] == 2){
            //get tax from calculated value
            $value = $this->product_price[$base.'tax'];
            if ($this->packingsheetConfig['hide_zero_tax_value'] == 0 || $value != 0)
                $this->product_price[$base.'print_tax'] = $value;
        }
    }

    public function showProductTotal($product,$order_item){
        $this->getProductTotal($product,$order_item);
        if ($this->showBaseValue)
            $this->getProductTotal($product,$order_item,'base_');
        $this->printValueLine($this->product_price['total_print'],$this->packingsheetConfig['product_line_total_title_xpos'],$this->y);
        if ($this->showBaseValue)
            $this->printValueLine($this->product_price['base_total_print'],$this->packingsheetConfig['product_line_total_title_xpos'],$this->y - 1.5 * $this->generalConfig['font_size_body'], false, 'base_currency');
    }

    private function getProductTotal($product,$order_item, $base = ''){
        $only_invoiced = ($this->packingsheetConfig['filter_items_by_status'] == 1)? true: false;
        if($only_invoiced){
            $qty = floatval($order_item->getData('qty_invoiced'));
        }else{
            $qty = floatval($order_item->getData('qty_ordered'));
        }
        //this function will get total from magento_order_item or will caculate from $this->product_price
        $this->product_price[$base.'total_original_price'] = $this->product_price[$base.'original_price'] * $qty;
        $this->product_price[$base.'total_print'] = null;
	
		//get value from magento order_item
        if ($this->packingsheetConfig['product_line_total_yn'] == 1)
            if ($this->packingsheetConfig['product_line_total_with_tax_yn'] == 1)
                $this->product_price[$base.'total_print'] = ($only_invoiced)?$order_item->getData($base.'row_invoiced'):$order_item->getData($base.'row_total_incl_tax');
            else $this->product_price[$base.'total_print'] = ($only_invoiced)?$order_item->getData($base.'row_invoiced'):$order_item->getData($base.'row_total');
        elseif ($this->packingsheetConfig['product_line_total_yn'] == 2){
            $tax_amount = 0;
            $discount_amount = 0;

			//get value total tax
            if ($this->packingsheetConfig['product_line_total_with_tax_yn'] == 1)
                $tax_amount = $this->product_price[$base.'total_tax'];

            //get value total discount
            if ($this->packingsheetConfig['product_line_total_with_discount_yn'] == 1)
                $discount_amount = $this->product_price[$base.'total_discount'];

            $this->product_price[$base.'total_print'] = $this->product_price[$base.'total_original_price'] + $discount_amount + $tax_amount;
        }
    }

    private function printValueLine($print_value,$x,$y,$was_include = false,$base_or_current_price = 'order_currency'){
        $storeId = $this->getStoreId();
        
	    if (!is_null($print_value)){
	        $page = $this->getPage();
	        $order = $this->getOrder();


            if ($base_or_current_price == 'order_currency')
                $currency_code = $this->orderCurrencyCode;
            else{
                $currency_code = $this->baseCurrencyCode;
            }

            $print_value_display = Mage::getModel('directory/currency')->setData('currency_code', $currency_code)
                                    ->format($print_value, array('display' =>Zend_Currency::NO_SYMBOL), false);

			$print_symbol = '';

            //missing this setting in system.xml
            //$this->packingsheetConfig['currency_codes_or_symbols'];

			if(($this->packingsheetConfig['currency_codes_or_symbols'] == 'codes') 
			|| ($this->packingsheetConfig['currency_codes_or_symbols'] == 'symbols') 
			|| ($this->packingsheetConfig['currency_codes_or_symbols'] == 'both')) {
				$order_currency_code = Mage::app()->getLocale()->currency($currency_code)->getShortname();
				
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
			
		    if ($was_include)
                $print_value_display = '('.$print_value_display.')';


            if ($base_or_current_price == 'base_currency'){
                $this->_setFont($page, 'italic', $this->generalConfig['font_size_body'], $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->base_currency_font_color);
            }else $this->_setFont($page, $this->generalConfig['font_style_body'], $this->generalConfig['font_size_body'], $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->generalConfig['font_color_body']);

            $this->_drawText($print_value_display, $x, $y);

            if ($base_or_current_price == 'base_currency')
                $this->_setFont($page, $this->generalConfig['font_style_body'], $this->generalConfig['font_size_body'], $this->generalConfig['font_family_body'], $this->generalConfig['non_standard_characters'], $this->generalConfig['font_color_body']);
        }
    }

    public function calucateSubtotalData(&$subtotal_data){
        if (isset($subtotal_data['subtotal_original_price']))
			$subtotal_data['subtotal_original_price'] += floatval($this->product_price['total_original_price']);
        else
			$subtotal_data['subtotal_original_price'] = floatval($this->product_price['total_original_price']);
      
	    if (isset($subtotal_data['subtotal_tax']))
			$subtotal_data['subtotal_tax'] += floatval($this->product_price['total_tax']);
        else
			$subtotal_data['subtotal_tax'] = floatval($this->product_price['total_tax']);
       
	    if (isset($subtotal_data['subtotal_discount']))
			$subtotal_data['subtotal_discount'] += floatval($this->product_price['total_discount']);
        else
			$subtotal_data['subtotal_discount'] = floatval($this->product_price['total_discount']);

        //caculate base value
        if ($this->showBaseValue){
            if (isset($subtotal_data['base_subtotal_original_price']))
                $subtotal_data['base_subtotal_original_price'] += floatval($this->product_price['base_total_original_price']);
            else
                $subtotal_data['base_subtotal_original_price'] = floatval($this->product_price['base_total_original_price']);

            if (isset($subtotal_data['base_subtotal_tax']))
                $subtotal_data['base_subtotal_tax'] += floatval($this->product_price['base_total_tax']);
            else
                $subtotal_data['base_subtotal_tax'] = floatval($this->product_price['base_total_tax']);

            if (isset($subtotal_data['base_subtotal_discount']))
                $subtotal_data['base_subtotal_discount'] += floatval($this->product_price['base_total_discount']);
            else
                $subtotal_data['base_subtotal_discount'] = floatval($this->product_price['base_total_discount']);
        }
    }
}