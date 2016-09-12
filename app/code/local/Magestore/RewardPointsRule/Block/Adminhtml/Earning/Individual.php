<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_RewardPoints
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * RewardPointsRule Earning Catalog Block
 * 
 * @category    Magestore
 * @package     Magestore_RewardPoints
 * @author      Magestore Developer
 */
class Magestore_RewardPointsRule_Block_Adminhtml_Earning_Individual extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_earning_individual';
        $this->_blockGroup = 'rewardpointsrule';
        $this->_headerText = Mage::helper('rewardpointsrule')->__('Manage Earning Points By Product <br><span style="font-size: 0.7em">Earning Points for products takes priority over catalog rules. (Shopping cart rules may still apply)</span>');

        $this->_removeButton('add');
    }
}
