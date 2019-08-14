<?php

class Officience_News_Block_Tags extends Officience_News_Block_Abstract {

    protected function _prepareLayout() {
        if ($head = $this->getLayout()->getBlock('head')) {
            // show breadcrumbs

            $moduleName = $this->getRequest()->getModuleName();
            $showBreadcrumbs = (int) Mage::getStoreConfig('offinews/news/showbreadcrumbs');
            if ($showBreadcrumbs && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
                $breadcrumbs->addCrumb('home', array(
                    'label' => Mage::helper('offinews')->__('Home'),
                    'title' => Mage::helper('offinews')->__('Go to Home Page'),
                    'link' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
                $breadcrumbs->addCrumb('offinews', array(
                    'label' => Mage::helper('offinews')->__(Mage::getStoreConfig('offinews/news/title')),
                    'title' => Mage::helper('offinews')->__('Return to %s', Mage::helper('offinews')->__('News')),
                    'link' => Mage::getUrl(Mage::helper('offinews')->getRoute())
                ));

                $breadcrumbs->addCrumb('tag', array(
                    'label' => Mage::helper('offinews')->__("Tag"),
                    'title' => Mage::helper('offinews')->__('Tag'),
                ));

                $breadcrumbs->addCrumb('tagitem', array(
                    'label' => Mage::helper('offinews')->__("Tag item"),
                    'title' => Mage::helper('offinews')->__('Tag item'),
                ));
            }

            if ($moduleName == 'offinews') {
                // set default meta data
                $head->setTitle(Mage::getStoreConfig('offinews/news/metatitle'));
                $head->setKeywords(Mage::getStoreConfig('offinews/news/metakeywords'));
                $head->setDescription(Mage::getStoreConfig('offinews/news/metadescription'));
                // set category meta data if defined
                $currentCategory = $this->getCurrentCategory();
                if ($currentCategory != null) {
                    if ($currentCategory->getTitle() != '') {
                        $head->setTitle($currentCategory->getTitle());
                    }
                    if ($currentCategory->getMetaKeywords() != '') {
                        $head->setKeywords($currentCategory->getMetaKeywords());
                    }
                    if ($currentCategory->getMetaDescription() != '') {
                        $head->setDescription($currentCategory->getMetaDescription());
                    }
                }
            }
        }
    }

    public function getCurrentCategory() {
        return false;
    }

    public function getNewsPost() {
        $tag = $this->getRequest()->getParam('tag');
        $tag = trim($tag);
        $tag = str_replace('-', ' ', $tag);
        $collection = Mage::getModel('offinews/news')->getCollection()
        ;
        $storeId = Mage::app()->getStore()->getId();
        $collection
                ->addEnableFilter(1)
                ->addStoreFilter($storeId)
                ->addStoreEnableFilter(1);
        $collection->getSelect()->where("main_table.tags LIKE '%" . $tag . "%'");
        return $collection;
    }

}
