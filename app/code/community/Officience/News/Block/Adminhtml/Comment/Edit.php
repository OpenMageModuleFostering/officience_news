<?php

class Officience_News_Block_Adminhtml_Comment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'offinews';
        $this->_controller = 'adminhtml_comment';

        $this->_updateButton('save', 'label', Mage::helper('offinews')->__('Save Comment'));
        $this->_updateButton('delete', 'label', Mage::helper('offinews')->__('Delete Comment'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('news_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'news_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'news_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if ( Mage::registry('news_data') && Mage::registry('news_data')->getId() ) {
            return Mage::helper('offinews')->__("Edit Comment By '%s'",
                $this->htmlEscape(Mage::registry('news_data')->getUser()));
        } else {
            return Mage::helper('offinews')->__('Add Comment');
        }
    }
}
