<?php

class Officience_News_Model_Mysql4_Comment extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('offinews/comment', 'comment_id');
    }
}
