<?php

class Officience_News_Block_Rss extends Mage_Rss_Block_Abstract {

    protected function _toHtml() {
        $rssObj = Mage::getModel('rss/rss');

        $data = array('title' => 'News',
            'description' => 'News',
            'link' => $this->getUrl('offinews/rss'),
            'charset' => 'UTF-8',
            'language' => Mage::getStoreConfig('general/locale/code')
        );

        $rssObj->_addHeader($data);

        $collection = $this->getNewsPost();
        if ($collection->getSize() > 0) {
            foreach ($collection as $item) {
                $data = array(
                    'title' => $item->getTitle(),
                    'link' => $item->getUrl(),
                    'description' => $item->getDescription(),
                );

                $rssObj->_addEntry($data);
            }
        } else {
            $data = array('title' => Mage::helper('offinews')->__('Cannot retrieve the news'),
                'description' => Mage::helper('offinews')->__('Cannot retrieve the news'),
                'link' => Mage::getUrl(),
                'charset' => 'UTF-8',
            );
            $rssObj->_addHeader($data);
        }

        return $rssObj->createRssXml();
    }

    public function getNewsPost() {
        $this->_showFlag = 1;
        $collection = Mage::getModel('offinews/news')->getCollection()
        ;
        $category = $this->getCategory();
        $storeId = Mage::app()->getStore()->getId();
        if ($category != null) {

            if ($category) {
                $tableName = Mage::getSingleton('core/resource')->getTableName('officience_news_category');
                $collection->getSelect()->join(array("t2" => $tableName), 'main_table.news_id = t2.news_id', 't2.category_id');
                $collection->getSelect()->where('t2.category_id =?', $category);
            }
        } 
        $collection
                ->addEnableFilter(1)
                ->addStoreFilter($storeId)
                ->addStoreEnableFilter(1);
        return $collection;
    }

    private function getCategory() {
        return Mage::registry('current_offi_news_cat_id');
    }

}
