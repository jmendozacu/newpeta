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
* File        Custemail.php
* @category   Moogento
* @package    pickPack
* @copyright  Copyright (c) 2016 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ 


class Moogento_Pickpack_Model_Source_Ordercommentoption
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        $return_array[] = array('value' => 0, 'label' => Mage::helper('pickpack')->__('No'));
        $return_array[] = array('value' => 1, 'label' => Mage::helper('pickpack')->__('Yes'));
        if (Mage::helper('pickpack')->isInstalled('Aitoc_Aitcheckoutfields')) {
            $return_array[] = array('value' => 3, 'label' => Mage::helper('pickpack')->__('Get Comment From Aitoc Aitcheckoutfields'));
        }

        return $return_array;
    }

}
