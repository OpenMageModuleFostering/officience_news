<?php

class Officience_News_Model_NewsStore extends Mage_Core_Model_Abstract {

    protected function _construct() {
        parent::_construct();
        $this->_init('offinews/newsStore');
    }
    
     
    public function deleteNewsStore($NewsId, $storeId) {

        $connection = Mage::getSingleton('core/resource')
                ->getConnection('core_write');
        $table = Mage::getSingleton('core/resource')->getTableName('officience_news_store');
        $condition = array(
            $connection->quoteInto('news_id=?', $NewsId),
            $connection->quoteInto('store_id=?', $storeId),
        );

        $connection->delete($table, $condition);
    }

    public function getDefaultValue($id, $store) {
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $maintable = Mage::getSingleton('core/resource')->getTableName('officience_news_store');
        ;

        $select = $db->select()
                ->from($maintable, array('default_value'))//->columns(array('default_value'))
                ->where('news_id = ?', $id)
                ->where('store_id =?', $store);
        $stmt = $db->query($select);
        $result = $stmt->fetchAll();
        return $result[0]['default_value'];
    }

}

?>
