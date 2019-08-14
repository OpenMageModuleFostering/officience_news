<?php

class Officience_News_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_category';
        $this->_blockGroup = 'offinews';
        $this->_headerText = Mage::helper('offinews')->__('Category Manager');
        $this->_addButtonLabel = Mage::helper('offinews')->__('Add Category');
        parent::__construct();
    }
}
