<?php

class Moogento_Pickpack_Model_Source_Shipping_Zone
{
    public function toOptionArray() {
        $options = array();

        if (Mage::helper('pickpack')->isInstalled('Moogento_CourierRules')) {
            $collection = Mage::getResourceModel('moogento_courierrules/zone_collection');
            foreach ($collection as $zone) {
                $options[] = array('value' => $zone->getId(), 'label' => $zone->getName());
            }
        }

        return $options;
    }
} 