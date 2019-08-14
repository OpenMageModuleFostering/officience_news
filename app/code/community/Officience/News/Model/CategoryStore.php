<?php

class Officience_News_Model_CategoryStore extends Mage_Core_Model_Abstract {

    protected function _construct() {
        parent::_construct();
        $this->_init('offinews/categoryStore');
    }

    public function deleteCategoryStore($categoryId, $storeId) {

        $connection = Mage::getSingleton('core/resource')
                ->getConnection('core_write');
        $table = Mage::getSingleton('core/resource')->getTableName('officience_category_store');
        $condition = array(
            $connection->quoteInto('category_id=?', $categoryId),
            $connection->quoteInto('store_id=?', $storeId),
        );

        $connection->delete($table, $condition);
    }

}

?>
