<?php

class Officience_News_Block_Adminhtml_News extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_news';
        $this->_blockGroup = 'offinews';
        $this->_headerText = Mage::helper('offinews')->__('News Manager');
        $this->_addButtonLabel = Mage::helper('offinews')->__('Add New Article');
        parent::__construct();
    }
}
