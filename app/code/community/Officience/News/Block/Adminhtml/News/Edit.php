<?php

class Officience_News_Block_Adminhtml_News_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'offinews';
        $this->_controller = 'adminhtml_news';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('offinews')->__('Save News Article'));
        $this->_updateButton('delete', 'label', Mage::helper('offinews')->__('Delete News Article'));

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

            function checkboxSwitch(){
                if (jQuery('#use_full_img').is(':checked')) {
                    jQuery('#image_short_content').parent().parent().css('display','none');
                } else {
                    jQuery('#image_short_content').parent().parent().css('display', 'table-row');
                    jQuery('#image_short_content').siblings('a').css('float', 'left');
                    jQuery('#image_short_content').siblings('a').css('margin-right', '4px');
                    jQuery('#image_short_content').parent().parent().css('width','155px');
                }
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('news_data') && Mage::registry('news_data')->getId()) {
            return Mage::helper('offinews')->__("Edit News Article '%s'",
                $this->htmlEscape(Mage::registry('news_data')->getTitle()));
        } else {
            return Mage::helper('offinews')->__('New post');
        }
    }
}
