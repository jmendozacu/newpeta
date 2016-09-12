<?php
/**
 * 
 * Date: 18.11.15
 * Time: 12:25
 */

class Moogento_Pickpack_Helper_Barcode extends Mage_Core_Helper_Abstract
{
    public function convertToBarcodeString($toBarcodeString, $barcode_type = 'code128') {
        if ($barcode_type !== 'code128') {
            $toBarcodeString = '*' . $toBarcodeString . '*';
        }

        $str = $toBarcodeString;
        $barcode_data = str_replace(' ', chr(128), $str);

        $checksum = 104; # must include START B code 128 value (104) in checksum
        for ($i = 0; $i < strlen($str); $i++) {
            $code128 = '';
            if (ord($barcode_data{$i}) == 128) {
                $code128 = 0;
            } elseif (ord($barcode_data{$i}) >= 32 && ord($barcode_data{$i}) <= 126) {
                $code128 = ord($barcode_data{$i}) - 32;
            } elseif (ord($barcode_data{$i}) >= 126) {
                $code128 = ord($barcode_data{$i}) - 50;
            }
            $checksum_position = $code128 * ($i + 1);
            $checksum += $checksum_position;
        }
        $check_digit_value = $checksum % 103;
        $check_digit_ascii = '';
        if ($check_digit_value <= 94) {
            $check_digit_ascii = $check_digit_value + 32;
        } elseif ($check_digit_value > 94) {
            $check_digit_ascii = $check_digit_value + 50;
        }
        $barcode_str = chr(154) . $barcode_data . chr($check_digit_ascii) . chr(156);
        $barcode_str = str_replace(' ', chr(128), $barcode_str);
        return $barcode_str;
    }

    public function getFontForType($barcode_type) {
        switch ($barcode_type) {
            case 'code128':
                $font_family_barcode = 'Code128bWin.ttf';
                break;
            case 'code39':
                $font_family_barcode = 'CODE39.ttf';
                break;
            case 'code39x':
                $font_family_barcode = 'CODE39X.ttf';
                break;
            default:
                $font_family_barcode = 'Code128bWin.ttf';
                break;
        }

        return $font_family_barcode;
    }

    public function getBarcodeSizeOnRealFont($barcodeString, $fontPath, $barcode_font_size){

        $mult = version_compare(GD_VERSION, '2.0', '>=') ? .75 : 1; //this will caculate base on pixel or point
        $rect = imagettfbbox($barcode_font_size * $mult, 0, $fontPath, $barcodeString);

        $minX = min(array($rect[0],$rect[2],$rect[4],$rect[6]));
        $maxX = max(array($rect[0],$rect[2],$rect[4],$rect[6]));
        $minY = min(array($rect[1],$rect[3],$rect[5],$rect[7]));
        $maxY = max(array($rect[1],$rect[3],$rect[5],$rect[7]));

        $width = ($maxX - $minX) * 1.05;

        return array(
            "width"  => $width,
            "height" => $maxY - $minY
        );
    }
}
