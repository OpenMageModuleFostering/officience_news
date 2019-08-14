<?php


class Officience_News_Model_Mysql4_Comment_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('offinews/comment');
    }

    public function addApproveFilter($status)
    {
        $this->getSelect()
            ->where('comment_status = ?', $status);
        return $this;
    }

    public function addNewsFilter($newsId)
    {
        $this->getSelect()
            ->where('news_id = ?', $newsId);
        return $this;
    }
}
