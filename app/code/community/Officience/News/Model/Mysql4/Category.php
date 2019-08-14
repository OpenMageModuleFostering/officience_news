<?php

class Officience_News_Model_Mysql4_Category extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('offinews/category', 'category_id');
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        $allStores = Mage::app()->getStores();
        $parentId = $object->getParentId();

        if ($parentId != 0) {
            $modelParentData = $this->_getPathParent($parentId);
            $pathInsert = $modelParentData['path'] . '/' . $object->getId();
        } else {
            $pathInsert = $object->getId();
        }
        $this->_updatePathCatId($object->getId(), $pathInsert);
        
        
        foreach ($allStores as $value) {
            $select = $this->_getReadAdapter()->select()
                    ->from($this->getTable('categoryStore'))
                    ->where('category_id = ?', $object->getCategoryId())
                    ->where('store_id = ?', $value->getStoreId());
            $where[] = $this->_getWriteAdapter()->quoteInto('category_id = ?', $object->getId());
            $where[] = $this->_getWriteAdapter()->quoteInto('store_id = ?', $value->getStoreId());
            if ($data = $this->_getReadAdapter()->fetchAll($select)) {
                $arrValue = array('category_id' => $object->getCategoryId(),
                    'store_id' => $value->getStoreId(),
                    'default_value' => $data[0]['default_value'],
                );
                $defaultValueArr = Mage::helper('offinews')->getDefaultValueData($data[0]);
                foreach ($defaultValueArr as $key => $defaultData) {
                    if ($defaultData) {
                        $arrValue[$key] = $object->getData($key);
                    }
                }
                $this->_getWriteAdapter()->update($this->getTable('categoryStore'), $arrValue, $where);
            } else {
                $arrData = array(
                    'category_id' => $object->getId(),
                    'store_id' => $value->getStoreId(),
                    'title' => $object->getTitle(),
                    'display_setting' => $object->getDisplaySetting(),
                    'path' => $object->getPath(),
                    'category_status' => $object->getCategoryStatus(),
                    'identifier' => $object->getIdentifier(),
                    'sort_order' => $object->getSortOrder(),
                    'description' => $object->getDescription(),
                    'meta_keywords' => $object->getMetaKeywords(),
                    'meta_description' => $object->getMetaDescription(),
                    'default_value' => Mage::helper('offinews')->listDefaultCat(),
                );
                $this->_getWriteAdapter()->insert($this->getTable('categoryStore'), $arrData);
            }
        }

        
        return parent::_afterSave($object);
    }

    private function _updatePathCatId($catId, $path) {
        try {

            $table = $this->getMainTable();
            $where = $this->_getWriteAdapter()->quoteInto('category_id = ?', $catId);
            $query = $this->_getWriteAdapter()->update($table, array('path' => $path), $where);
        } catch (Exception $e) {
            
        }
    }

    private function _getPathParent($parentId) {
        try {
            $db = Mage::getSingleton('core/resource')->getConnection('core_read');
            $table = $this->getMainTable();
            $where = $this->_getReadAdapter()->quoteInto('category_id = ?', $parentId);
            $query = $this->_getReadAdapter()->select()->from($table, array('path'))
                    ->where($where);
            $data = $db->query($query);
            return $data->fetch();
        } catch (Exception $e) {
            
        }
    }

    public function getDefaultValue($id, $store) {
        $maintable = Mage::getSingleton('core/resource')->getTableName('officience_category_store');
        ;
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $where = $this->_getReadAdapter()->quoteInto('category_id = ? AND', $id) .
                $this->_getReadAdapter()->quoteInto('store_id =?', $store);
        $select = $this->_getReadAdapter()->select()
                ->from($maintable, array('default_value'))
                ->where($where);
        $data = $db->query($select);
        return $data->fetch();
    }

}
