<?php

class Officience_News_Model_Category extends Mage_Core_Model_Abstract {

    protected function _construct() {
        parent::_construct();
        $this->_init('offinews/category');
    }

    public function getDefaultValue($id, $store) {
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $maintable = Mage::getSingleton('core/resource')->getTableName('officience_category_store');
        ;

        $select = $db->select()
                ->from($maintable, array('default_value'))
                ->where('category_id = ?', $id)
                ->where('store_id =?', $store);
        $stmt = $db->query($select);
        $result = $stmt->fetchAll();
        return $result[0]['default_value'];
    }

    public function getUrl() {
        $urlOption = Mage::helper('offinews')->getUrloption();
        $url = "";
        if ($urlOption == 2) {
            $arr = array();
            if ($this->getPath()) {
                $pathArr = explode('/', $this->getPath());
                if (count($pathArr) <= 1) {
                    $arr[] = $this->getIdentifier();
                } else {
                    $table = Mage::getSingleton('core/resource')->getTableName('officience_category_store');

                    $db = Mage::getSingleton('core/resource')->getConnection('core_read');
                    $select = $db->select()
                            ->from($table, array('category_id', 'identifier'))
                            ->where('category_id in (?)', $pathArr);

                    $value = $db->query($select)->fetchAll();
                    $arrTemp = array();
                    foreach ($value as $item) {
                        $arrTemp[$item['category_id']] = $item['identifier'];
                    }
                    $arUrl = array();
                    foreach ($pathArr as $item2) {
                        $arUrl[] = $arrTemp[$item2];
                    }
                    $urlarr = implode($arUrl, '/');
                    $arr[] = $urlarr;
                }
            }
            $url = Mage::getUrl(Mage::helper('offinews')->getRoute()) . implode($arr, '/') . Mage::helper('offinews')->getNewsitemUrlSuffix();
        } else {
            $url = Mage::getUrl(Mage::helper('offinews')->getRoute()) . 'category/' . $this->getIdentifier() . Mage::helper('offinews')->getNewsitemUrlSuffix();
        }
        return $url;
    }

}
