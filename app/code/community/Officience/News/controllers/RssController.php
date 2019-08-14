<?php

class Officience_News_RssController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        if ((int) Mage::getStoreConfig('offinews/rss/enable')) {
            if ($category = $this->getRequest()->getParam('category')) {
                $collection = Mage::getModel('offinews/category')->getCollection()
                        ->addStoreFilter(Mage::app()->getStore()->getId())
                        ->filterByIdentifier($category)
                        ->getFirstItem()
                ;
                if (Mage::registry('current_offi_news_cat_id')) {
                    Mage::unregister('current_offi_news_cat_id');
                }
                Mage::register('current_offi_news_cat_id', $collection->getId());

                if (Mage::registry('current_offi_news_cat_identifier')) {
                    Mage::unregister('current_offi_news_cat_identifier');
                }
                Mage::register('current_offi_news_cat_identifier', $collection->getIdentifier());
            }



            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->_forward('NoRoute');
        }
    }

    public function noRouteAction($coreRoute = null) {
        $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
        $this->getResponse()->setHeader('Status', '404 File not found');

        $pageId = Mage::getStoreConfig('web/default/cms_no_route');
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultNoRoute');
        }
    }

}
