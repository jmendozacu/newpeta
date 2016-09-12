<?php
/**
 * 
 * Date: 18.11.15
 * Time: 12:53
 */

class Moogento_Pickpack_Helper_Config_Color extends Moogento_Pickpack_Helper_Config
{
    public function getPdfColor($name) {
        switch($name) {
            case 'red_bkg_color':
                $color = new Zend_Pdf_Color_Html('lightCoral');
                break;
            case 'grey_bkg_color':
                $color = new Zend_Pdf_Color_GrayScale(0.7);
                break;
            case 'dk_cyan_bkg_color':
                $color = new Zend_Pdf_Color_Html('darkCyan'); //darkOliveGreen
                break;
            case 'bk_og_bkg_color':
                $color = new Zend_Pdf_Color_Html('darkOliveGreen');
                break;
            case 'black_color':
                $color = new Zend_Pdf_Color_Rgb(0, 0, 0);
                break;
            case 'red_color':
                $color = new Zend_Pdf_Color_Html('darkRed');
                break;
            case 'dk_grey_bkg_color':
            case 'grey_color':
                $color = new Zend_Pdf_Color_GrayScale(0.3);
                break;
            case 'greyout_color':
                $color = new Zend_Pdf_Color_GrayScale(0.6);
                break;
            case 'white_color':
                $color = new Zend_Pdf_Color_GrayScale(1);
                break;
            case 'grayout_color':
                $color = "#888888";
                break;
            default:
                Mage::throwException('PDF color with name "'.$name.'" not found.');
                break;
        }

        return $color;
    }

    public function adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
}