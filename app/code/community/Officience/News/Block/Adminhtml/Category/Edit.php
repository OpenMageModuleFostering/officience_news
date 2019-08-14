<?php

class Officience_News_Block_Adminhtml_Category_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        $this->_objectId = 'id';
        $this->_blockGroup = 'offinews';
        $this->_controller = 'adminhtml_category';
        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Category'));
        $this->_updateButton('delete', 'label', $this->__('Delete Category'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('cat_data') && Mage::registry('cat_data')->getId()) {
            return $this->__("Edit News Category '%s'", $this->htmlEscape(Mage::registry('cat_data')->getTitle()));
        } else {
            return $this->__('Add News Category');
        }
    }


}
