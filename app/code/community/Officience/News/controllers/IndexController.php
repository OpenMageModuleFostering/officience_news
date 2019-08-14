<?php

class Officience_News_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        //// check if this category is allowed to view
        if ($category = $this->getRequest()->getParam('category')) {
            $collection = Mage::getModel('offinews/category')->getCollection()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->filterByIdentifier($category)
                    ->getFirstItem()
                    ;
            if (count($collection->getData()) < 1) {
                $this->_forward('NoRoute');
                return;
            }
            if (!Mage::registry('current_offi_news_cat_id')) {
                Mage::register('current_offi_news_cat_id', $collection->getId());
            }
            if (!Mage::registry('current_offi_news_cat_identifier')) {
                Mage::register('current_offi_news_cat_identifier', $collection->getIdentifier());
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function tagAction() {
        $urlOption = Mage::helper('offinews')->getUrloption();

        $collection = Mage::getModel('offinews/news')->getCollection()
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ->getFirstItem();
        if (count($collection->getData()) < 1) {
            $this->_forward('NoRoute');
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

}
