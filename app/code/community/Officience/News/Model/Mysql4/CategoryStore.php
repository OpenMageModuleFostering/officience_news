<?php

class Officience_News_Model_Mysql4_CategoryStore extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('offinews/categoryStore', array('category_id,store_id'));
    }

}
