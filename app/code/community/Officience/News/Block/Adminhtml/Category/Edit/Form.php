<?php

class Officience_News_Block_Adminhtml_Category_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

     public function _construct() {
        parent::_construct();
        $this->setId('news_form');
        $this->setTitle($this->__('Category Infomation'));
    }
    
    protected function _prepareForm() {
        $model = Mage::registry('cat_data');

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'), 'store' => $this->getRequest()->getParam('store', 0))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
?>

