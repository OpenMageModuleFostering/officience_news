<?php

class Officience_News_Model_Comment extends Mage_Core_Model_Abstract
{
    public function _construct(){
        $this->_init('offinews/comment');
    }

    public function load($id, $field=null){
        return parent::load($id, $field);
    }
}
