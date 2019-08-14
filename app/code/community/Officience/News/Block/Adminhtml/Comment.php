<?php


class Officience_News_Block_Adminhtml_Comment extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_comment';
        $this->_blockGroup = 'offinews';
        $this->_headerText = Mage::helper('offinews')->__('Comment Manager');
        parent::__construct();
        $this->_removeButton('add');
    }
}
