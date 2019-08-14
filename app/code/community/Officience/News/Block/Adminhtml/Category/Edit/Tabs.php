<?php

class Officience_News_Block_Adminhtml_Category_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function _construct() {
        parent::_construct();
        $this->setId('news_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Category'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form', array(
            'label' => $this->__('General Infomation'),
            'title' => $this->__('General Infomation'),
            'content' => $this->getLayout()->createBlock('offinews/adminhtml_category_edit_tab_form')->initForm()->toHtml(),
        ));
         $this->addTab('additional', array(
            'label' => $this->__('Meta'),
            'title' => $this->__('Meta'),
            'content' => $this->getLayout()->createBlock('offinews/adminhtml_category_edit_tab_additional')->initForm()->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
?>

