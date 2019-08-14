<?php

class Officience_News_Block_Abstract extends Mage_Core_Block_Template {

    protected $_pagesCount = null;
    protected $_currentPage = null;
    protected $_itemsOnPage = 10;
    protected $_itemsLimit;
    protected $_pages;
    protected $_latestItemsCount = 2;
    protected $_showFlag = 0;

    protected function _construct() {
        $this->_currentPage = $this->getRequest()->getParam('page');
        if (!$this->_currentPage) {
            $this->_currentPage = 1;
        }

        $itemsPerPage = (int) Mage::getStoreConfig('offinews/news/itemsperpage');
        if ($itemsPerPage > 0) {
            $this->_itemsOnPage = $itemsPerPage;
        }

        $itemsLimit = (int) $this->getData('itemslimit');
        if ($itemsLimit == null) {
            $itemsLimit = (int) Mage::getStoreConfig('offinews/news/itemslimit');
        }
        if ($itemsLimit > 0) {
            $this->_itemsLimit = $itemsLimit;
        } else {
            $this->_itemsLimit = null;
        }

        $latestItemsCount = (int) Mage::getStoreConfig('offinews/news/latestitemscount');
        if ($latestItemsCount > 0) {
            $this->_latestItemsCount = $latestItemsCount;
        }
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
        if ($this->_itemsLimit != null && $this->_itemsLimit < $collection->getSize()) {
            $this->_pagesCount = ceil($this->_itemsLimit / $this->_itemsOnPage);
        } else {
            $this->_pagesCount = ceil($collection->getSize() / $this->_itemsOnPage);
        }
        for ($i = 1; $i <= $this->_pagesCount; $i++) {
            $this->_pages[] = $i;
        }
        $this->setLastPageNum($this->_pagesCount);

        $offset = $this->_itemsOnPage * ($this->_currentPage - 1 );
        if ($this->_itemsLimit != null) {
            $_itemsCurrentPage = $this->_itemsLimit - $offset;
            if ($_itemsCurrentPage > $this->_itemsOnPage) {
                $_itemsCurrentPage = $this->_itemsOnPage;
            }
            $collection->getSelect()->limit($_itemsCurrentPage, $offset);
        } else {
            $collection->getSelect()->limit($this->_itemsOnPage, $offset);
        }
        return $collection;
    }

    protected function getCategory() {
        return Mage::registry('current_offi_news_cat_id');
    }

    public function getTopLink() {
        $route = Mage::helper('offinews')->getRoute();
        $title = Mage::helper('offinews')->__(Mage::getStoreConfig('offinews/news/title'));
        if ($this->getParentBlock() && $this->getParentBlock() != null) {
            $this->getParentBlock()->addLink($title, $route, $title, true, array(), 15, null, 'class="top-link-news"');
        }
    }

    public function getPages() {
//        if ((int) Mage::getStoreConfig('offinews/news/perpage') != 0) {
        $collection = Mage::getModel('offinews/news')->getCollection();
        if ($category = $this->getCategory()) {
            $modelCat = Mage::getModel('offinews/category')->load($category);
            $url = $modelCat->getUrl() . '?page=';
        } else {
            $url = Mage::getUrl(Mage::helper('offinews')->getRoute()) . '?page=';
        }
        $storeId = Mage::app()->getStore()->getId();
        if ($category != null) {

            if ($category) {
                $tableName = Mage::getSingleton('core/resource')->getTableName('officience_news_category');
                $collection->getSelect()->join(array("t2" => $tableName), 'main_table.news_id = t2.news_id', 't2.category_id');
                $collection->getSelect()->where('t2.category_id =?', $category);
            }
        }
        $currentPage = (int) $this->getRequest()->getParam('page');
        $links = "<div class='post-page-break'>";
        if ($currentPage > 1) {
            $links .= '<div class="left"><a href="' . $url . ($this->_currentPage - 1) . '" >< Newer Posts</a></div>';
        }
        if ($currentPage < $this->_pagesCount || $currentPage == 1) {
            $links .= '<div class="right"><a href="' . $url . ($this->_currentPage + 1) . '" >Older Posts ></a></div>';
        }
        $links .= "</div>";

        echo $links;
        //}
    }

    public function isFirstPage() {
        if ($this->_currentPage == 1) {
            return true;
        }
        return false;
    }

    public function isLastPage() {
        if ($this->_currentPage == $this->_pagesCount) {
            return true;
        }
        return false;
    }

    public function isPageCurrent($page) {
        if ($page == $this->_currentPage) {
            return true;
        }
        return false;
    }

    public function getPageUrl($page) {
        if (Mage::app()->getRequest()->getModuleName() == Mage::helper('offinews')->getRoute()) {
            if ($category = $this->getCategoryKey()) {
                return $this->getUrl('*', array('category' => $category, 'page' => $page));
            } else {
                return $this->getUrl('*', array('page' => $page));
            }
        } else {
            if (strstr(Mage::helper("core/url")->getCurrentUrl(), '?')) {
                $sign = '&';
            } else {
                $sign = '?';
            }
            if (strstr(Mage::helper("core/url")->getCurrentUrl(), 'page=')) {
                return preg_replace('(page=[0-9]+)', 'page=' . $page, Mage::helper("core/url")->getCurrentUrl());
            } else {
                return Mage::helper("core/url")->getCurrentUrl() . $sign . 'page=' . $page;
            }
        }
    }

    public function getNextPageUrl() {
        $page = $this->_currentPage + 1;
        return $this->getPageUrl($page);
    }

    public function getPreviousPageUrl() {
        $page = $this->_currentPage - 1;
        return $this->getPageUrl($page);
    }

    public function getCurrentCategory() {
        $storeId = Mage::app()->getStore()->getId();
        if ($catId = Mage::registry('current_offi_news_cat_id')) {
            $categories = Mage::getModel('offinews/category')->load($catId);
            return $categories;
        }
        return null;
    }

    protected function _toHtml() {
        $html = parent::_toHtml();
        if ($this->_showFlag == 1) {
            $html = $html . '<div align="right" class="copyright">&copy Developed by <a href="http://officience.com/" target="_blank">Officience</a></div>';
        }
        return $html;
    }

    protected function getChildCatBlog($parentId) {
        $storeId = Mage::app()->getStore()->getId();
        $data = Mage::getModel('offinews/category')->getCollection()
                ->addFieldToFilter('parent_id', array('eq' => $parentId))
                ->addStoreFilter($storeId)
                ->addStoreEnableFilter(1)
                ->setOrder('sort_order', 'ASC')
        ;
        return $data;
    }

}
