<?php

class Officience_News_Model_Mysql4_News extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('offinews/news', 'news_id');
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        $allStores = Mage::app()->getStores();

        foreach ($allStores as $value) {
            $select = $this->_getReadAdapter()->select()
                    ->from($this->getTable('newsStore'))
                    ->where('news_id = ?', $object->getId())
                    ->where('store_id = ?', $value->getStoreId());
            $where[] = $this->_getWriteAdapter()->quoteInto('news_id = ?', $object->getId());
            $where[] = $this->_getWriteAdapter()->quoteInto('store_id = ?', $value->getStoreId());
            if ($data = $this->_getReadAdapter()->fetchAll($select)) {
                $arrValue = array('news_id' => $object->getId(),
                    'store_id' => $value->getStoreId(),
                    'default_value' => $data[0]['default_value'],
                );
                $defaultValueArr = Mage::helper('offinews')->getDefaultValueData($data[0]);
                foreach ($defaultValueArr as $key => $defaultData) {
                    if ($defaultData && $key != 'default_value') {
                        $arrValue[$key] = $object->getData($key);
                    }
                }
                $this->_getWriteAdapter()->update($this->getTable('newsStore'), $arrValue, $where);
            } else {
                $arrData = array(
                    'news_id' => $object->getId(),
                    'store_id' => $value->getStoreId(),
                    'title' => $object->getTitle(),
                    'identifier' => $object->getIdentifier(),
                    'description' => $object->getDescription(),
                    'full_content' => $object->getFullContent(),
                    'news_status' => $object->getNewsStatus(),
                    'publicate_from_date' => $object->getPublicateFromDate(),
                    'publicate_to_date' => $object->getPublicateToDate(),
                    'publicate_from_time' => $object->getPublicateFromTime(),
                    'publicate_to_time' => $object->getPublicateToTime(),
                    'author' => $object->getAuthor(),
                    'meta_keywords' => $object->getMetaKeywords(),
                    'meta_description' => $object->getMetaDescription(),
                    'comments_enabled' => $object->getCommentsEnabled(),
                    'tags' => $object->getTags(),
                    'sort_order' => $object->getSortOrder(),
                    'default_value' => Mage::helper('offinews')->listDefaultNews(),
                );
                $this->_getWriteAdapter()->insert($this->getTable('newsStore'), $arrData);
            }
        }



        $condition = $this->_getWriteAdapter()->quoteInto('news_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('newsCategory'), $condition);
        foreach ((array) $object->getData('news_category') as $category) {
            $data = array();
            $data['news_id'] = $object->getId();
            $data['category_id'] = $category;
            $this->_getWriteAdapter()->insert($this->getTable('newsCategory'), $data);
        }
        return parent::_afterSave($object);
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object) {

        $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('newsCategory'))
                ->where('news_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $categories = array();
            foreach ($data as $row) {
                $categories[] = $row['category_id'];
            }
            $object->setData('news_category', $categories);
        }

        return parent::_afterLoad($object);
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('news/newsCategory'), 'news_id=' . $object->getId());
        $adapter->delete($this->getTable('news/comment'), 'news_id=' . $object->getId());
    }

}
