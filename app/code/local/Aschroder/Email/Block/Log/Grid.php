<?php

/**
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2013 ASchroder Consulting Ltd
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Aschroder_Email_Block_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('emailLogGrid');
        $this->setDefaultSort('email_id');
        $this->setDefaultDir('DESC');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('aschroder_email/email_log')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('email_id', array(
            'header' => Mage::helper('aschroder_email')->__('Id'),
            'width' => '30px',
            'index' => 'email_id',
        ));
        $this->addColumn('sent', array(
            'header' => Mage::helper('aschroder_email')->__('Sent'),
            'width' => '60px',
            'index' => 'log_at',
        ));
        $this->addColumn('subject', array(
            'header' => Mage::helper('aschroder_email')->__('Subject'),
            'width' => '160px',
            'index' => 'subject',
        ));
        $this->addColumn('email_to', array(
            'header' => Mage::helper('aschroder_email')->__('To'),
            'width' => '160px',
            'index' => 'email_to',
        ));


        return parent::_prepareColumns();
    }

    /**
     * Row click url
     *
     * @return string
     */
    public function getRowUrl($row) {
        return $this->getUrl('*/*/view', array('email_id' => $row->getId()));
    }

}
