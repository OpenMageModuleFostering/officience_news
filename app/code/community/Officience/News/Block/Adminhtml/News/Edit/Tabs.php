<?php


class Officience_News_Block_Adminhtml_News_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('news_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('offinews')->__('Main Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('info', array(
            'label'     => Mage::helper('offinews')->__('Main Information'),
            'content'   => $this->getLayout()->createBlock('offinews/adminhtml_news_edit_tab_info')->initForm()->toHtml(),
        ));
        
         $this->addTab('category', array(
            'label'     => Mage::helper('offinews')->__('Category'),
            'content'   => $this->getLayout()
                ->createBlock('offinews/adminhtml_news_edit_tab_category')->toHtml(),
        ));

        $this->addTab('additional', array(
            'label'     => Mage::helper('offinews')->__('Additional Options'),
            'content'   => $this->getLayout()
                ->createBlock('offinews/adminhtml_news_edit_tab_additional')->initForm()->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
