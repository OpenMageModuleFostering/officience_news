<?php

class Officience_News_Model_Mysql4_News_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('offinews/news');
    }

    public function addEnableFilter($status) {
        $this->getSelect()
                ->where('main_table.news_status = ?', $status);
        return $this;
    }

    public function addFilterByIdentifier($identifier) {
        $this->getSelect()
                ->where('news_store_table.identifier = ?', $identifier);
        return $this;
    }

    public function addCategoryFilter($categoryId) {
        $this->getSelect()->join(
                        array('news_category_table' => $this->getTable('news_category')), 'main_table.news_id = news_category_table.news_id', array()
                )->join(
                        array('category_table' => $this->getTable('category')), 'news_category_table.category_id = category_table.category_id', array()
                )->join(
                        array('category_store_table' => $this->getTable('category_store')), 'category_table.category_id = category_store_table.category_id', array()
                )
                ->where('category_table.identifier = "' . $categoryId . '"')
                ->where('category_store_table.store_id in (?)', array(0, Mage::app()->getStore()->getId()))
        ;
        return $this;
    }

    public function addStoreEnableFilter($status) {
        $this->getSelect()
                ->where('news_store_table.news_status = ?', $status);
        return $this;
    }

    public function addStoreFilter($store) {
        $this->addFieldToSelect(array('thumbnail', 'created_time'));
        $this->getSelect()->join(
                array('news_store_table' => $this->getTable('newsStore')), 'main_table.news_id = news_store_table.news_id and news_store_table.store_id=' . $store, array('news_store_table.title',
            'news_store_table.identifier',
            'news_store_table.description',
            'news_store_table.full_content',
            'news_store_table.news_status',
            'news_store_table.publicate_from_date',
            'news_store_table.publicate_to_date',
            'news_store_table.publicate_from_time',
            'news_store_table.publicate_to_time',
            'news_store_table.author',
            'news_store_table.meta_keywords',
            'news_store_table.meta_description',
            'news_store_table.comments_enabled',
            'news_store_table.tags',
            'news_store_table.sort_order',
                )
        )
        // ->where('news_store_table.store_id in (?)', array(0, $store))
        ;
        $this->getSelect()->distinct();
        return $this;
    }

}
