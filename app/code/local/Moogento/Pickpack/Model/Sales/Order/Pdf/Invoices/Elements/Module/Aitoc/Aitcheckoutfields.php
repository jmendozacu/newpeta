<?php
/**
 * User: namdg
 * Date: 23/06/2016
 * Time: 8:50 CH
 */

class Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Module_Aitoc_Aitcheckoutfields extends Moogento_Pickpack_Model_Sales_Order_Pdf_Invoices_Elements_Module_Abstract
{
    public function getAitocOrderComments(){
        $order = $this->getOrder();
        $storeId = $this->getStoreId();
        $order_id = $order->getId();

        $comment_array = array();

        $Aitcheckoutfields_object  = Mage::getModel('aitcheckoutfields/aitcheckoutfields');

        if (is_object($Aitcheckoutfields_object)){
            $attribute_list = $Aitcheckoutfields_object->getOrderCustomData($order_id, $storeId, true, true);
            foreach ($attribute_list as $attribute){
                if ($attribute['code'] == 'comment_box'){
                    $comment_array[] = array(
                        'label' => $attribute['label'],
                        'value' => $attribute['value']
                    );
                }
            }
        }

        return $comment_array;
    }
}