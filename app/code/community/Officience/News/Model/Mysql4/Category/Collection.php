<?php

class Officience_News_Model_Mysql4_Category_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('offinews/category');
    }

    public function addEnableFilter($status) {
        $this->getSelect()
                ->where('main_table.category_status = ?', $status);
        return $this;
    }

    public function addStoreEnableFilter($status) {
        $this->getSelect()
                ->where('category_store_table.category_status = ?', $status);
        return $this;
    }

    public function filterByIdentifier($identifier) {
        $this->getSelect()
                ->where('category_store_table.identifier = ?', $identifier);
        return $this;
    }

    public function filterIdentifierIn($identifier) {
        $this->getSelect()
                ->where('category_store_table.identifier in (?)', $identifier);
        return $this;
    }

    public function addStoreFilterPath($store) {
        $this->addFieldToSelect(array('parent_id', 'level'));
        $this->getSelect()->join(
                array('category_store_table' => $this->getTable('categoryStore')), 'main_table.category_id = category_store_table.category_id and category_store_table.store_id=' . $store, array(
            'category_store_table.identifier', 'category_store_table.path'
                )
        )
        ;
        $this->getSelect()->distinct();
        return $this;
    }

    public function addStoreFilter($store) {
        $this->addFieldToSelect(array('thumbnail', 'parent_id', 'level', 'path'));
        $this->getSelect()->join(
                array('category_store_table' => $this->getTable('categoryStore')), 'main_table.category_id = category_store_table.category_id and category_store_table.store_id=' . $store, array('category_store_table.title',
            'category_store_table.store_id',
            'category_store_table.identifier',
            'category_store_table.description',
            'category_store_table.sort_order',
            'category_store_table.meta_keywords',
            'category_store_table.meta_description',
                )
        )
        ;
        $this->getSelect()->distinct();
        return $this;
    }

}
