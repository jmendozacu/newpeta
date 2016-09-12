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
* File        Data.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2016 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 

class Moogento_Pickpack_Helper_Font extends Mage_Core_Helper_Abstract
{
    protected $_font;
    protected $_fontSize;
    protected $font_path;
    protected $font_addon_path;
    protected $custom_path;
    protected $general_path;

    public function __construct() {
        $this->font_path = Mage::helper('pickpack')->getFontPath();
        $this->font_addon_path = Mage::helper('pickpack')->getFontAddonPath();
        $this->custom_path = Mage::helper('pickpack')->getFontCustomPath();
        $this->general_path = Mage::helper('pickpack')->getFontGeneralPath();
    }

    public function setFontRegular($object, $size = 10) {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $object->setFont($font, $size);
        return $font;
    }

    public function setFontBold($object, $size = 10) {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $object->setFont($font, $size);
        return $font;
    }

    public function setFontItalic($object, $size = 10) {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }

    public function setFontBoldItalic($object, $size = 10) {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }

    public function getFont($style = 'regular', $size = 10, $font_family = 'helvetica', $non_standard_characters = 0, $get_only_path = false) {
		$font_file_path = '';
		$non_standard_characters = 0; // forcing this after new font system
        switch ($font_family) {
           
		    case 'hebrew':
                $font_file_path = $this->font_addon_path . 'SILEOTSR.ttf';
                break;
				
				//skin/adminhtml/default/default/moogento/pickpack/fonts/
				//OpenSans-Light-webfont.ttf
				//OpenSans-Regular-webfont.ttf
				//OpenSans-Italic-webfont.ttf
				//OpenSans-Bold-webfont.ttf
				//OpenSans-BoldItalic-webfont.ttf
				//OpenSans-ExtraBoldItalic-webfont.ttf
				
	        case 'opensans':
	            switch ($style) {
	                case 'light' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-Light-webfont.ttf';
	                    break;
					case 'regular' :
	                    $font_file_path = $this->general_path . 'OpenSans-Regular-webfont.ttf';
	                    break;
	                case 'semibold' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-Semibold-webfont.ttf';
	                    break;
					case 'bold' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-Bold-webfont.ttf';
	                    break;
	                case 'italic' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-Italic-webfont.ttf';
	                    break;						
	                case 'semibolditalic' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-SemiboldItalic-webfont.ttf';
	                    break;
	                case 'bolditalic' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-BoldItalic-webfont.ttf';
	                    break;
	                default:
	                    $font_file_path = $this->general_path . 'OpenSans-Regular-webfont.ttf';
	                    break;
	            }
	            break;

		        case 'noto':
		            switch ($style) {
		                case 'light' :
						case 'regular' :
		                    $font_file_path = $this->font_path . 'noto/NotoSans-Regular.ttf';
		                    break;
		                case 'semibold' :
						case 'bold' :
		                    $font_file_path = $this->font_path . 'noto/NotoSans-Bold.ttf';
		                    break;
		                case 'italic' :
		                    $font_file_path = $this->font_path . 'noto/NotoSans-Italic.ttf';
		                    break;						
		                case 'semibolditalic' :
		                case 'bolditalic' :
		                    $font_file_path = $this->font_path . 'noto/NotoSans-BoldItalic.ttf';
		                    break;
		                default:
		                    $font_file_path = $this->font_path . 'noto/NotoSans-Regular.ttf';
		                    break;
	            }
	            break;
				
		        case 'droid':
		            switch ($style) {
		                case 'light' :
						case 'regular' :
		                    $font_file_path = $this->font_path . 'droid/DroidSerif-Regular.ttf';
		                    break;
		                case 'semibold' :
						case 'bold' :
		                    $font_file_path = $this->font_path . 'droid/DroidSerif-Bold.ttf';
		                    break;
		                case 'italic' :
		                    $font_file_path = $this->font_path . 'droid/DroidSerif-Italic.ttf';
		                    break;						
		                case 'semibolditalic' :
		                case 'bolditalic' :
		                    $font_file_path = $this->font_path . 'droid/DroidSerif-BoldItalic.ttf';
		                    break;
		                default:
		                    $font_file_path = $this->font_path . 'droid/DroidSerif-Regular.ttf';
		                    break;
	            }
	            break;
				
		        case 'handwriting':
		            switch ($style) {
		                case 'light' :
						case 'regular' :
		                case 'italic' :
		                case 'bolditalic' :
		                case 'semibolditalic' :
							$font_file_path = $this->font_path . 'daniel/daniel.ttf';
		                    break;
		                case 'semibold' :
						case 'bold' :
		                    $font_file_path = $this->font_path . 'daniel/daniel-Bold.ttf';
		                    break;
		                default:
							$font_file_path = $this->font_path . 'daniel/daniel.ttf';
							break;
		            }
		            break;
											
	            case 'helvetica':
	                switch ($style) {
	                    case 'light':						
	                    case 'regular' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
							$font_file_path = $this->font_addon_path . 'arial.ttf';
	                        break;
	                    case 'italic' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
							$font_file_path = $this->font_addon_path . 'ariali.ttf';
	                        break;
						case 'semibold':
	                    case 'bold' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
							$font_file_path = $this->font_addon_path . 'arialbd.ttf';
	                        break;
		                case 'semibolditalic' :
	                    case 'bolditalic' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
							$font_file_path = $this->font_addon_path . 'arialbi.ttf';
	                        break;
	                    default:
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
							$font_file_path = $this->font_addon_path . 'arial.ttf';
	                        break;
	                }
	                break;

	            case 'courier':
	                switch ($style) {
	                    case 'light':						
	                    case 'regular' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
							$font_file_path = $this->font_addon_path . 'cour.ttf';
	                        break;
	                    case 'italic' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_ITALIC);
							$font_file_path = $this->font_addon_path . 'couri.ttf';
	                        break;
						case 'semibold':
	                    case 'bold' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD);
							$font_file_path = $this->font_addon_path . 'courbd.ttf';
	                        break;
		                case 'semibolditalic' :
	                    case 'bolditalic' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD_ITALIC);
							$font_file_path = $this->font_addon_path . 'courbi.ttf';
	                        break;
	                    default:
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
							$font_file_path = $this->font_addon_path . 'cour.ttf';
	                        break;
	                }
	                break;				

	            case 'times':
	                switch ($style) {
	                    case 'light':						
	                    case 'regular' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
							$font_file_path = $this->font_addon_path . 'times.ttf';
	                        break;
	                    case 'italic' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC);
							$font_file_path = $this->font_addon_path . 'timesi.ttf';
	                        break;
						case 'semibold':
	                    case 'bold' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
							$font_file_path = $this->font_addon_path . 'timesbd.ttf';
	                        break;
		                case 'semibolditalic' :
	                    case 'bolditalic' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC);
							$font_file_path = $this->font_addon_path . 'timesbi.ttf';
	                        break;
	                    default:
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
							$font_file_path = $this->font_addon_path . 'times.ttf';
	                        break;
	                }
	                break;

	            case 'msgothic':
	                $font_file_path = $this->font_addon_path . 'msgothic.ttf';
	                break;

	            case 'tahoma':
					$font_file_path = $this->font_addon_path . 'tahoma.ttf';
	                break;

	            case 'garuda':
	                switch ($style) {
	                    case 'light':						
	                    case 'regular' :
	                        $font_file_path = $this->font_addon_path . 'garuda.ttf';
	                        break;
	                    case 'italic' :
	                        $font_file_path = $this->font_addon_path . 'garudai.ttf';
	                        break;
						case 'semibold':
	                    case 'bold' :
	                        $font_file_path = $this->font_addon_path . 'garudabd.ttf';
	                        break;
		                case 'semibolditalic' :
	                    case 'bolditalic' :
	                        $font_file_path = $this->font_addon_path . 'garudabi.ttf';
	                        break;
	                    default:
	                        $font_file_path = $this->font_addon_path . 'garuda.ttf';
	                        break;
	                }
	                break;

	            case 'sawasdee':
	                switch ($style) {
	                    case 'light':						
	                    case 'regular' :
	                        $font_file_path = $this->font_addon_path . 'sawasdee.ttf';
	                        break;
	                    case 'italic' :
	                        $font_file_path = $this->font_addon_path . 'sawasdeei.ttf';
	                        break;
	                    case 'bold' :
	                        $font_file_path = $this->font_addon_path . 'sawasdeebd.ttf';
	                        break;
		                case 'semibolditalic' :							
	                    case 'bolditalic' :
	                        $font_file_path = $this->font_addon_path . 'sawasdeebi.ttf';
	                        break;
	                    default:
	                        $font_file_path = $this->font_addon_path . 'sawasdee.ttf';
	                        break;
	                }
	                break;

	            case 'kinnari':
	                switch ($style) {
	                    case 'light':						
	                    case 'regular' :
	                        $font_file_path = $this->font_addon_path . 'kinnari.ttf';
	                        break;
	                    case 'italic' :
	                        $font_file_path = $this->font_addon_path . 'kinnarii.ttf';
	                        break;
						case 'semibold':
						case 'bold' :
	                        $font_file_path = $this->font_addon_path . 'kinnaribd.ttf';
	                        break;
		                case 'semibolditalic' :
	                    case 'bolditalic' :
	                        $font_file_path = $this->font_addon_path . 'kinnaribi.ttf';
	                        break;
	                    default:
	                        $font_file_path = $this->font_addon_path . 'kinnari.ttf';
	                        break;
	                }
	                break;

	            case 'purisa':
	                $font_file_path = $this->font_addon_path . 'purisa.ttf';
	                break;
	            case 'traditional_chinese':
	                $font_file_path = $this->font_addon_path . 'traditional_chinese.ttf';
	                break;
	            case 'simplified_chinese':
	                $font_file_path = $this->font_addon_path . 'simplified_chinese.ttf';
	                break;
	            case 'custom':
	                $font_file_path = $this->custom_path . $style;
	                break;
					
	            default:
	                switch ($style) {
	                    case 'light':						
	                    case 'regular' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
							$font_file_path = $this->font_addon_path . 'arial.ttf';
	                        break;
	                    case 'italic' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
							$font_file_path = $this->font_addon_path . 'ariali.ttf';
	                        break;
						case 'semibold':
	                    case 'bold' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
							$font_file_path = $this->font_addon_path . 'arialbd.ttf';
	                        break;
		                case 'semibolditalic' :
	                    case 'bolditalic' :
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
							$font_file_path = $this->font_addon_path . 'arialbi.ttf';
	                        break;
	                    default:
							$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
							$font_file_path = $this->font_addon_path . 'arial.ttf';
	                        break;
	                }
	                break;
        }

		if (!isset($font)){
			if (isset($font_file_path) && ($font_file_path != '')){
				try{
					if(file_exists($font_file_path))
						if(strstr($font_file_path,'chinese') !== false)
							$font = Zend_Pdf_Font::fontWithPath($font_file_path);
						else
							$font = Zend_Pdf_Font::fontWithPath($font_file_path, Zend_Pdf_Font::EMBED_DONT_COMPRESS);
					else {
						$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
						Mage::log('Font not found at '.$font_file_path.' - substituted Helvetica', null, 'moogento_pickpack.log');
					}

				} catch(Exception $e) {
					Mage::log($e->getMessage(). ' - Error, font not found at '.$font_file_path.' - switched Helvetica', null, 'moogento_pickpack.log');
				}
			}
		}
		if ($get_only_path){
			return $font_file_path;
		}else return $font;
    }

    public function parseString($string, $font = null, $fontsize = null) {
        if (is_null($font))
            $font = $this->_font;
        if (is_null($fontsize))
            $fontsize = $this->_fontsize;

        $drawingString = iconv('UTF-8', 'UTF-16BE//TRANSLIT//IGNORE', $string);
        $characters    = array();
        for ($i = 0; $i < strlen($drawingString); $i++) {
            $characters[] = (ord($drawingString[$i++]) << 8) | ord($drawingString[$i]);
        }
        if (!is_object($characters)) {
            $glyphs      = $font->glyphNumbersForCharacters($characters);
            $widths      = $font->widthsForGlyphs($glyphs);
            $stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontsize;
            return $stringWidth;
        } else {
            // fudge for other extensions bad characters
            return (strlen($string) * $fontsize);
        }
    }


    public function getMaxCharMessage($padded_right, $font_size_options, $font_temp, $padded_left=30) {
        $maxWidthPage_message = $padded_right - $padded_left;
        $font_temp_message = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $font_size_compare_message = $font_size_options;
        $line_width_message = $this->parseString('12345abcde', $font_temp, $font_size_compare_message);
        $char_width_message = $line_width_message / 10;
        $max_chars_message = round($maxWidthPage_message / $char_width_message);
        return $max_chars_message;
    }

    public function getFontName2($font = 'helvetica', $style = 'regular', $non_standard_characters = 0) {
		$font_file_path = '';
		$non_standard_characters = 0; // forcing this after new font system
		
        switch ($font) {

	        case 'opensans':
	            switch ($style) {
	                case 'light' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-Light-webfont.ttf';
	                    break;
					case 'regular' :
	                    $font_file_path = $this->general_path . 'OpenSans-Regular-webfont.ttf';
	                    break;
	                case 'semibold' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-Semibold-webfont.ttf';
	                    break;
					case 'bold' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-Bold-webfont.ttf';
	                    break;
	                case 'italic' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-Italic-webfont.ttf';
	                    break;						
	                case 'semibolditalic' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-SemiboldItalic-webfont.ttf';
	                    break;
	                case 'bolditalic' :
	                    $font_file_path = $this->font_path . 'opensans/OpenSans-BoldItalic-webfont.ttf';
	                    break;
	                default:
	                    $font_file_path = $this->general_path . 'OpenSans-Regular-webfont.ttf';
	                    break;
	            }
	            break;
				
	        case 'noto':
	            switch ($style) {
	                case 'light' :
					case 'regular' :
	                    $font_file_path = $this->font_path . 'noto/NotoSans-Regular.ttf';
	                    break;
	                case 'semibold' :
					case 'bold' :
	                    $font_file_path = $this->font_path . 'noto/NotoSans-Bold.ttf';
	                    break;
	                case 'italic' :
	                    $font_file_path = $this->font_path . 'noto/NotoSans-Italic.ttf';
	                    break;						
	                case 'semibolditalic' :
	                case 'bolditalic' :
	                    $font_file_path = $this->font_path . 'noto/NotoSans-BoldItalic.ttf';
	                    break;
	                default:
	                    $font_file_path = $this->font_path . 'noto/NotoSans-Regular.ttf';
	                    break;
            }
            break;
			
	        case 'droid':
	            switch ($style) {
	                case 'light' :
					case 'regular' :
	                    $font_file_path = $this->font_path . 'droid/DroidSerif-Regular.ttf';
	                    break;
	                case 'semibold' :
					case 'bold' :
	                    $font_file_path = $this->font_path . 'droid/DroidSerif-Bold.ttf';
	                    break;
	                case 'italic' :
	                    $font_file_path = $this->font_path . 'droid/DroidSerif-Italic.ttf';
	                    break;						
	                case 'semibolditalic' :
	                case 'bolditalic' :
	                    $font_file_path = $this->font_path . 'droid/DroidSerif-BoldItalic.ttf';
	                    break;
	                default:
	                    $font_file_path = $this->font_path . 'droid/DroidSerif-Regular.ttf';
	                    break;
            }
            break;
			
	        case 'handwriting':
	            switch ($style) {
	                case 'light' :
					case 'regular' :
	                case 'italic' :
	                case 'bolditalic' :
	                case 'semibolditalic' :
						$font_file_path = $this->font_path . 'daniel/daniel.ttf';
	                    break;
	                case 'semibold' :
					case 'bold' :
	                    $font_file_path = $this->font_path . 'daniel/daniel-Bold.ttf';
	                    break;
	                default:
						$font_file_path = $this->font_path . 'daniel/daniel.ttf';
						break;
	            }
	            break;
						
            case 'helvetica':
                switch ($style) {
                    case 'light':
                    case 'regular':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                        else
                            $font_file_path = $this->font_addon_path . 'arial.ttf';
                        break;
                    case 'italic':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
                        else
                            $font_file_path = $this->font_addon_path . 'ariali.ttf';
                        break;
                    case 'semibold':
                    case 'bold':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
                        else
                            $font_file_path = $this->font_addon_path . 'arialbd.ttf';
                        break;
                    case 'semibolditalic':
                    case 'bolditalic':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
                        else
                            $font_file_path = $this->font_addon_path . 'arialbi.ttf';
                        break;
                    default:
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                        else
                            $font_file_path = $this->font_addon_path . 'arial.ttf';
                        break;
                }
                break;

            case 'courier':
                switch ($style) {
                    case 'light':
                    case 'regular':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
                        else
                            $font_file_path = $this->font_addon_path . 'cour.ttf';
                        break;
                    case 'italic':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_ITALIC);
                        else
                            $font_file_path = $this->font_addon_path . 'couri.ttf';
                        break;
                    case 'semibold':
                    case 'bold':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD);
                        else
                            $font_file_path = $this->font_addon_path . 'courbd.ttf';
                        break;
                    case 'semibolditalic':
                    case 'bolditalic':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD_ITALIC);
                        else
                            $font_file_path = $this->font_addon_path . 'courbi.ttf';
                        break;
                    default:
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
                        else
                            $font_file_path = $this->font_addon_path . 'cour.ttf';
                        break;
                }
                break;

            case 'times':
                switch ($style) {
                    case 'light':
                    case 'regular':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
                        else
                            $font_file_path = $this->font_addon_path . 'times.ttf';
                        break;
                    case 'italic':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_ITALIC);
                        else
                            $font_file_path = $this->font_addon_path . 'timesi.ttf';
                        break;
					case 'semibold':						
                    case 'bold':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD);
                        else
                            $font_file_path = $this->font_addon_path . 'timesbd.ttf';
                        break;
	                case 'semibolditalic' :
                    case 'bolditalic':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC);
                        else
                            $font_file_path = $this->font_addon_path . 'timesbi.ttf';
                        break;
                    default:
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
                        else
                            $font_file_path = $this->font_addon_path . 'times.ttf';
                        break;
                }
                break;

            case 'msgothic':
                $font_file_path = $this->font_addon_path . 'msgothic.ttf';
                break;

            case 'tahoma':
                $font_file_path = $this->font_addon_path . 'tahoma.ttf';
                break;

            case 'garuda':
                switch ($style) {
                    case 'light':
                    case 'regular':
                        $font_file_path = $this->font_addon_path . 'garuda.ttf';
                        break;
                    case 'italic':
                        $font_file_path = $this->font_addon_path . 'garudai.ttf';
                        break;
					case 'semibold':
                    case 'bold':
                        $font_file_path = $this->font_addon_path . 'garudabd.ttf';
                        break;
	                case 'semibolditalic' :
                    case 'bolditalic':
                        $font_file_path = $this->font_addon_path . 'garudabi.ttf';
                        break;
                    default:
                        $font_file_path = $this->font_addon_path . 'garuda.ttf';
                        break;
                }
                break;

            case 'sawasdee':
                switch ($style) {
                    case 'light':
                    case 'regular':
                        $font_file_path = $this->font_addon_path . 'sawasdee.ttf';
                        break;
                    case 'italic':
                        $font_file_path = $this->font_addon_path . 'sawasdeei.ttf';
                        break;
                    case 'bold':
					case 'semibold':
                        $font_file_path = $this->font_addon_path . 'sawasdeebd.ttf';
                        break;
	                case 'semibolditalic' :
                    case 'bolditalic':
                        $font_file_path = $this->font_addon_path . 'sawasdeebi.ttf';
                        break;
                    default:
                        $font_file_path = $this->font_addon_path . 'sawasdee.ttf';
                        break;
                }
                break;

            case 'kinnari':
                switch ($style) {
                    case 'light':
                    case 'regular':
                        $font_file_path = $this->font_addon_path . 'kinnari.ttf';
                        break;
                    case 'italic':
                        $font_file_path = $this->font_addon_path . 'kinnarii.ttf';
                        break;
                    case 'bold':
					case 'semibold':
                        $font_file_path = $this->font_addon_path . 'kinnaribd.ttf';
                        break;
	                case 'semibolditalic' :
                    case 'bolditalic':
                        $font_file_path = $this->font_addon_path . 'kinnaribi.ttf';
                        break;
                    default:
                        $font_file_path = $this->font_addon_path . 'kinnari.ttf';
                        break;
                }
                break;

            case 'purisa':
                $font_file_path = $this->font_addon_path . 'purisa.ttf';
                break;

            case 'custom':
	            $font_file_path = $this->custom_path . $style;
                break;

            default:
                switch ($style) {
                    case 'light':
                    case 'regular':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                        else
                            $font_file_path = $this->font_addon_path . 'arial.ttf';
                        break;
                    case 'italic':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
						else
                            $font_file_path = $this->font_addon_path . 'ariali.ttf';
                        break;
                    case 'bold':
					case 'semibold':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
                        else
                            $font_file_path = $this->font_addon_path . 'arialbd.ttf';
                        break;
	                case 'semibolditalic' :
                    case 'bolditalic':
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
                        else
                            $font_file_path = $this->font_addon_path . 'arialbi.ttf';
                        break;
                    default:
                        if ($non_standard_characters == 0)
                            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                        else
                            $font_file_path = $this->font_addon_path . 'arial.ttf';
                        break;
                }
                break;
        }

		if(isset($font_file_path) && ($font_file_path != '')) {		
	    	try{
				if(file_exists($font_file_path))
					if(strstr($font_file_path,'chinese') !== false)
						$font = Zend_Pdf_Font::fontWithPath($font_file_path);
					else
						$font = Zend_Pdf_Font::fontWithPath($font_file_path, Zend_Pdf_Font::EMBED_DONT_COMPRESS);
				else {
					$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
	           	 	Mage::log('Font not found at '.$font_file_path.' - substituted Helvetica', null, 'moogento_pickpack.log');
				}

	        } catch(Exception $e) {
				Mage::log($e->getMessage(). ' - Error, font not found at '.$font_file_path.' - switched Helvetica', null, 'moogento_pickpack.log');
			}
		}
        return $font;
    }

	public function checkCurrencySymbol($page,$text,$charEncoding){
		try{
			$textObj = $page->getFont()->encodeString($text, $charEncoding);
			if(strpos($textObj, '?') !== false){
				return false;
			}
		}catch (Exception $e){
			Mage::log($e->getMessage(). ' - Warning, symbol not support at this font', null, 'moogento_pickpack.log');
			return false;
		}
		return true;
	}

	//this function will word wrap text base on real font with given width, it slow but work correctly
	public function wordWrapOnRealFont($text_area, $font_family, $font_style, $font_size, $max_width){
		$array_text_line = explode("\n", $text_area);
		$array_wraped_text = array();
		$run_time = 0;

		foreach ($array_text_line as $text_line){
			$text_line = trim($text_line);
			$words_array = $this->mbStringToArray($text_line);
			$text_line_word_count = count($words_array);
			if ($text_line_word_count == 0){
				$array_wraped_text[] = "";
			}else{
				while ($text_line_word_count) {
					//in case some logic went wrong
					if ($run_time >=100) break;
					$run_time ++;

					$break_point = $this->trimTextOnRealFont($text_line, $font_family, $font_style, $font_size, $max_width, true, $words_array);
					if ($break_point >= $text_line_word_count){
						$array_wraped_text[] = $text_line;
						break;
					}else{
						try {
							$array_wraped_text[] = implode(array_slice($words_array, 0, $break_point));
							$words_array = array_slice($words_array, $break_point, $text_line_word_count);
							$text_line = implode($words_array);
							$text_line_word_count = count($words_array);
						} catch (Exception $e) {
						}
					}
				}
			}

		}

		return $array_wraped_text;
	}

	//this function will trim text base on real font with given width, it slow but work correctly
	public function trimTextOnRealFont($text, $font_family, $font_style, $font_size, $max_width, $split_text_for_wrap_function = false, $words_array = null){
		$text = trim($text);

		if ($split_text_for_wrap_function) $trim_symbol = '';
		else $trim_symbol = '...';

		//check if text not empty
		if ((!$text) || $text == "") return $text;

		$mult = version_compare(GD_VERSION, '2.0', '>=') ? .75 : 1; //this will caculate base on pixel or point
		$font_temp = $this->getFont($font_style, $font_size, $font_family, 0, true);

		$image_text_box = imagettfbbox($font_size * $mult, 0, $font_temp, $text);
		if ($image_text_box[2] <= $max_width){
			//no need to trim in this case
			$wraped_text = $text;
			$real_numbers_word = mb_strlen($wraped_text);
		}else{
			if (is_null($words_array))
				$words_array = $this->mbStringToArray($text);

			$guest_numbers_word = $this->guestNumberWordNeedForWrap($words_array, $font_size, $mult, $font_temp, $max_width);

			$temp_text = implode(array_slice($words_array, 0, $guest_numbers_word));

			$image_text_box = imagettfbbox($font_size * $mult, 0, $font_temp, $temp_text . $trim_symbol);
			if ($image_text_box[2] > $max_width) {
				$real_numbers_word = $this->loopDownWrapText($words_array, $guest_numbers_word - 1, $temp_text, $max_width, $font_size, $mult, $font_temp, $trim_symbol);
			}elseif ($image_text_box[2] < $max_width){
				$real_numbers_word = $this->loopUpWrapText($words_array, $guest_numbers_word, $temp_text, $max_width, $font_size, $mult, $font_temp, $trim_symbol);
			}elseif ($image_text_box[2] == $max_width){
				$real_numbers_word = $guest_numbers_word;
			}

			$wraped_text = mb_substr($text,0,$real_numbers_word,"UTF-8");
			$wraped_text = $wraped_text. $trim_symbol;
		}

		//this function will return vaule base on it is part of wordWrapOnRealFont() function or just trim text
		if (!$split_text_for_wrap_function){
			return $wraped_text;
		}else{
			return $real_numbers_word;
		}
	}

	//sub function of trimTextOnRealFont()
	private function guestNumberWordNeedForWrap($words_array, $font_size, $mult, $font_temp, $max_width){
		$count_word = count($words_array);
		if ($count_word > 5)
			$sample_number_word = 5;
		else $sample_number_word = $count_word;
		$temp_text = implode(array_slice($words_array, 0, $sample_number_word));
		$image_text_box = imagettfbbox($font_size * $mult, 0, $font_temp, $temp_text);
		$average_word_width = $image_text_box[2] / $sample_number_word;

		$numbers_word = round($max_width / $average_word_width);

		return $numbers_word;
	}

	//sub function of trimTextOnRealFont()
	private function loopUpWrapText($words_array, $numbers_word, $prev_text, $max_width, $font_size, $mult, $font_temp, $trim_symbol){
		//in case some logic wrong
		if ($numbers_word >= 1000) return "...";

		$temp_text = $prev_text.$words_array[$numbers_word];

		$image_text_box = imagettfbbox($font_size * $mult, 0, $font_temp, $temp_text . $trim_symbol);
		if ($image_text_box[2] > $max_width) {
			return $numbers_word - 1;
		}elseif ($image_text_box[2] == $max_width){
			return $numbers_word;
		}elseif ($image_text_box[2] < $max_width){
			return $this->loopUpWrapText($words_array, $numbers_word + 1, $temp_text, $max_width, $font_size, $mult, $font_temp, $trim_symbol);
		}
	}

	//sub function of trimTextOnRealFont()
	private function loopDownWrapText($words_array, $numbers_word, $prev_text, $max_width, $font_size, $mult, $font_temp, $trim_symbol){
		//in case some logic wrong
		if ($numbers_word <= 0) return "...";

		$temp_text = implode(array_slice($words_array, 0, $numbers_word));
		$image_text_box = imagettfbbox($font_size * $mult, 0, $font_temp, $temp_text . $trim_symbol);
		if ($image_text_box[2] <= $max_width) {
			return $numbers_word;
		}elseif ($image_text_box[2] > $max_width){
			return $this->loopDownWrapText($words_array, $numbers_word - 1, $temp_text, $max_width, $font_size, $mult, $font_temp, $trim_symbol);
		}
	}

	//sub function of trimTextOnRealFont() and wordWrapOnRealFont()
	private function mbStringToArray ($string) {
		$strlen = mb_strlen($string);
		while ($strlen) {
			$array[] = mb_substr($string,0,1,"UTF-8");
			$string = mb_substr($string,1,$strlen,"UTF-8");
			$strlen = mb_strlen($string);
		}
		return $array;
	}
}
