<?php

class Officience_News_Model_Mysql4_CategoryStore_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('offinews/categoryStore');
    }

}
