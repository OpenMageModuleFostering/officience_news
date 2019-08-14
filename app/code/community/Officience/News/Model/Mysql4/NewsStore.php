<?php

class Officience_News_Model_Mysql4_NewsStore extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('offinews/newsStore', array('news_id,store_id'));
    }
    

}
