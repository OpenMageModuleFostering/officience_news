<?php

class Officience_News_Block_Category extends Officience_News_Block_Abstract {

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

                if (Mage::helper('offinews')->getUrloption() == 2) {
                    if (Mage::registry('current_offi_news_cat_path')) {
                        $catPath = explode('/', Mage::registry('current_offi_news_cat_path'));
                        if (count($catPath) > 0) {
                            $countPath = count($catPath);
                            $countKey = 0;
                            foreach ($catPath as $key => $catItem) {
                                $countKey++;
                                $modelCatItem = Mage::getModel('offinews/category')->load($catItem);
                                if ($modelCatItem->getTitle() != "") {
                                    if ($countKey != $countPath) {
                                        $breadcrumbs->addCrumb('category-' . $key, array(
                                            'label' => $modelCatItem->getTitle(),
                                            'title' => Mage::helper('offinews')->__('Return to ' . $modelCatItem->getTitle()),
                                            'link' => $modelCatItem->getUrl())
                                        );
                                    } else {
                                        $breadcrumbs->addCrumb('category', array(
                                            'label' => $modelCatItem->getTitle(),
                                            'title' => Mage::helper('offinews')->__('Return to ' . $modelCatItem->getTitle()),
                                        ));
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if (Mage::registry('current_offi_news_cat_id')) {
                        $titleCat = Mage::getModel('offinews/category')->load(Mage::registry('current_offi_news_cat_id'))->getTitle();
                        $breadcrumbs->addCrumb('category', array(
                            'label' => $titleCat,
                            'title' => Mage::helper('offinews')->__('Return to ' . $titleCat),
                        ));
                    }
                }
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

    public function getUrlRss() {
        $curRequest = $this->getRequest()->getPathInfo();
        if (substr($curRequest, 0, strlen('/' . Mage::helper('offinews')->getRoute())) == '/' . Mage::helper('offinews')->getRoute()) {
            $curRequest = str_replace('/' . Mage::helper('offinews')->getRoute(), Mage::helper('offinews')->getRoute() . '/rss', $curRequest);
        }
        return Mage::getUrl() . $curRequest;
    }

    public function getSubCategory() {
        $data = array();
        if ($this->getCategory()) {
            $data = $this->getChildCatBlog($this->getCategory());
        } else {
            $data = $this->getChildCatBlog(0);
        }
        return $data;
    }

}
