<?php

class Officience_News_Block_Newspost extends Mage_Core_Block_Template {

    protected $_pagesCount = null;
    protected $_currentPage = null;
    protected $_itemsOnPage = 10;
    protected $_pages;

    protected function _construct() {
        $this->_currentPage = $this->getRequest()->getParam('page');
        if (!$this->_currentPage) {
            $this->_currentPage = 1;
        }

        $itemsPerPage = (int) Mage::getStoreConfig('offinews/comments/commentsperpage');
        if ($itemsPerPage > 0) {
            $this->_itemsOnPage = $itemsPerPage;
        }
    }

    protected function _prepareLayout() {
        if ($head = $this->getLayout()->getBlock('head')) {
            $newspost = $this->getNewsPost();

            if ($newspost == null) {
                return;
            }
            $showBreadcrumbs = (int) Mage::getStoreConfig('offinews/news/showbreadcrumbs');
            if ($showBreadcrumbs && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
                $breadcrumbs->addCrumb('home', array(
                    'label' => Mage::helper('offinews')->__('Home'),
                    'title' => Mage::helper('offinews')->__('Go to Home Page'),
                    'link' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)
                ));

                $breadcrumbs->addCrumb('offinews', array(
                    'label' => Mage::helper('offinews')->__(Mage::getStoreConfig('offinews/news/title')),
                    'title' => Mage::helper('offinews')->__('Return to %s', Mage::helper('offinews')->__('News')),
                    'link' => Mage::getUrl(Mage::helper('offinews')->getRoute())
                ));

                if (Mage::helper('offinews')->getUrloption() == 2) {
                    if (Mage::registry('current_offi_news_cat_path')) {
                        $catPath = explode('/', Mage::registry('current_offi_news_cat_path'));
                        if (count($catPath) > 0) {
                            foreach ($catPath as $key => $catItem) {
                                $modelCatItem = Mage::getModel('offinews/category')->load($catItem);
                                if ($modelCatItem->getTitle() != "") {
                                    $breadcrumbs->addCrumb('category', array(
                                        'label' => $modelCatItem->getTitle(),
                                        'title' => Mage::helper('offinews')->__('Return to ' . $modelCatItem->getTitle()),
                                        'link' => $modelCatItem->getUrl())
                                    );
                                }
                            }
                        }
                    }
                } else {
                    if (Mage::registry('current_offi_news_cat_id')) {
                        $loadCurCat = Mage::getModel('offinews/category')->load(Mage::registry('current_offi_news_cat_id'));
                        $breadcrumbs->addCrumb('category', array(
                            'label' => $loadCurCat->getTitle(),
                            'title' => Mage::helper('offinews')->__('Return to %s', $loadCurCat->getTitle()),
                            'link' => $loadCurCat->getUrl()
                        ));
                    }
                }



                $breadcrumbs->addCrumb('item', array(
                    'label' => $newspost->getTitle(),
                    'title' => $newspost->getTitle()
                ));
            }

            $head->setTitle($newspost->getTitle());
            if ($newspost->getMetaKeywords() != '') {
                $head->setKeywords($newspost->getMetaKeywords());
            } else {
                $head->setKeywords(Mage::getStoreConfig('offinews/news/metakeywords'));
            }
            if ($newspost->getMetaDescription() != '') {
                $head->setDescription($newspost->getMetaDescription());
            } else {
                $head->setDescription(Mage::getStoreConfig('offinews/news/metadescription'));
            }
        }
    }

    public function getNewsPost() {
        return Mage::registry('newspost');
    }

    public function getComments() {
        $newspost = $this->getNewsPost();

        $collection = Mage::getModel('offinews/comment')->getCollection()
                ->addNewsFilter($newspost->getNewsId())
                ->addApproveFilter(Officience_News_Helper_Data::APPROVED_STATUS)
                ->setOrder('created_time ', 'asc');
        $this->_pagesCount = ceil($collection->getSize() / $this->_itemsOnPage);
        for ($i = 1; $i <= $this->_pagesCount; $i++) {
            $this->_pages[] = $i;
        }
        $this->setLastPageNum($this->_pagesCount);

        $collection->setPageSize($this->_itemsOnPage);
        $collection->setCurPage($this->_currentPage);

        return $collection;
    }

    public function getRequireLogin() {
        return Mage::getStoreConfig('offinews/comments/need_login');
    }

    public function getPrintLogoUrl() {
        // load html logo
        $logo = Mage::getStoreConfig('sales/identity/logo_html');
        if (!empty($logo)) {
            $logo = 'sales/store/logo_html/' . $logo;
        }

        // load default logo
        if (empty($logo)) {
            $logo = Mage::getStoreConfig('sales/identity/logo');
            if (!empty($logo)) {
                // prevent tiff format displaying in html
                if (strtolower(substr($logo, -5)) === '.tiff' || strtolower(substr($logo, -4)) === '.tif') {
                    $logo = '';
                } else {
                    $logo = 'sales/store/logo/' . $logo;
                }
            }
        }

        // buld url
        if (!empty($logo)) {
            $logo = Mage::getStoreConfig('web/unsecure/base_media_url') . $logo;
        } else {
            $logo = '';
        }

        return $logo;
    }

    public function getPrintLogoText() {
        return Mage::getStoreConfig('sales/identity/address');
    }

    public function getLang() {
        if (!$this->hasData('lang')) {
            $this->setData('lang', substr(Mage::app()->getLocale()->getLocaleCode(), 0, 2));
        }
        return $this->getData('lang');
    }

    public function getAbsoluteFooter() {
        return Mage::getStoreConfig('design/footer/absolute_footer');
    }

    public function getBodyClass() {
        return $this->_getData('body_class');
    }

    public function contentFilter($content) {
        $helper = Mage::helper('cms');
        $processor = $helper->getPageTemplateProcessor();
        $html = $processor->filter($content);
        return $html;
    }

    protected function _toHtml() {
        $html = parent::_toHtml();
        return $html . '<div align="right" class="copyright">&copy Developed by <a href="http://officience.com/">Officience</a></div>';
    }

}
