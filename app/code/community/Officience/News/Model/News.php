<?php

class Officience_News_Model_News extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('offinews/news');
    }

    public function getUrl() {
        $urlOption = Mage::helper('offinews')->getUrloption();
        $url = "";
        if ($urlOption == 2) {
            $catUrl = Mage::app()->getRequest()->getPathInfo();
            if (substr($catUrl, 0, 1) == '/') {
                $catUrl = substr($catUrl, 1);
            }
            if (substr($catUrl, -1) == '/') {
                $catUrl = substr($catUrl, 0, -1);
            }
            $urlSuffix = '/' . $this->getIdentifier() . Mage::helper('offinews')->getNewsitemUrlSuffix();
            $catUrl = str_replace(Mage::helper('offinews')->getNewsitemUrlSuffix(), '', $catUrl);
            $url = Mage::getUrl() . $catUrl . $urlSuffix;
        } else {
            $url = Mage::getUrl(Mage::helper('offinews')->getRoute()) . $this->getIdentifier() . Mage::helper('offinews')->getNewsitemUrlSuffix();
        }
        return $url;
    }

    public function getCommentsCount() {
        $model = Mage::getModel('offinews/comment')->getCollection()
                ->addFieldToFilter('news_id', $this->getId())
                ->count();
        return $model;
    }

    public function getUrlPrint() {
        $urlOption = Mage::helper('offinews')->getUrloption();
        $url = "";
        if ($urlOption == 2) {
            $catUrl = str_replace(Mage::helper('offinews')->getNewsitemUrlSuffix(), '', Mage::registry('current_offi_news_cat_url'));
            $catUrl = str_replace(Mage::getUrl(Mage::helper('offinews')->getRoute()), '', $catUrl);
            if ($catUrl) {
                $catUrl = $catUrl . '/';
            }
            $url = Mage::getUrl(Mage::helper('offinews')->getRoute() . '/print') . $catUrl . $this->getIdentifier() . Mage::helper('offinews')->getNewsitemUrlSuffix();
        } else {
            $url = Mage::getUrl(Mage::helper('offinews')->getRoute()) . $this->getIdentifier() . Mage::helper('offinews')->getNewsitemUrlSuffix() . '?mode=print';
        }
        return $url;
    }

    /**
     * Reset all model data
     *
     * @return Officience_News_Model_News
     */
    public function reset() {
        $this->setData(array());
        $this->setOrigData();
        $this->_attributes = null;
        return $this;
    }

    public function getTimePublic() {
        if ($this->getPublicateFromDate()) {
            return Mage::helper('offinews')->formatDate($this->getCreatedTime());
        } else {
            return Mage::helper('offinews')->formatDate($this->getPublicateFromDate());
        }
    }

}
