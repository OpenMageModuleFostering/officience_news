<?php

class Officience_News_Block_Adminhtml_News_Edit_Tab_Category extends Mage_Adminhtml_Block_Widget_Form {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('officience/news/listCategory.phtml');
    }

    public function getListMenuCatBlog() {

        $data = $this->getChildCatBlog(0);
        $childrenWrapClass = '';
        $storeId = $this->getRequest()->getParam('store', 0);
        $html = $this->_getHtmlCat($data, $childrenWrapClass, $this->getNewsCategory(), $storeId);
        return $html;
    }

    public function getNewsCategory() {
        if ($this->getRequest()->getParam('id', 0)) {
            $data = Mage::registry('news_data');
            return $data->getData('news_category');
        }
        return array();
    }

    protected function getChildCatBlog($parentId) {
        $data = Mage::getModel('offinews/category')->getCollection()
                ->addFieldToFilter('parent_id', array('eq' => $parentId))
                // ->addFieldToFilter('category_status', array('eq' => 1))
                ->setOrder('sort_order', 'ASC')
                ->load()
                ->getData();
        foreach ($data as &$items) {
            $items['child'] = $this->getChildCatBlog($items['category_id']);
        }
        return $data;
    }

    protected function _getHtmlCat($menuTree, $childrenWrapClass, $arrCat = array(), $storeId = 0) {

        $html = '';
        $counter = 1;
        foreach ($menuTree as $node) {

            $childrenCount = count($menuTree);
            $children = $node['child'];
            //$node['First'] = ($counter == 1);
            //$node['Last'] = ($counter == $childrenCount);
            $parentLevel = $node['level'];
            $childLevel = (is_null($parentLevel) || $parentLevel == 0) ? 0 : $parentLevel;
            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($node) . ' >';
            $html.='<div class="offinews-node-leaf">';
            $html .='<img id="ext-gen11" class="offinews-node-icon" unselectable="on" src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS) . 'spacer.gif">';
            $html .='<input type="checkbox" class="l-tcb ' . ($storeId ? ' disabled ' : '') . '" ';
            if (count($arrCat) && in_array($node['category_id'], $arrCat)) {
                $html.=' checked="checked" ';
            }
            if ($storeId) {
                $html.= ' disabled= "" ';
            }
            $html.=' name="news_category[]" value="' . $node['category_id'] . '">';
            $html .= '<a><span>'
                    . $this->escapeHtml($node['title']) . '</span></a>';
            if (!empty($children)) {
                $html .= '<ul class="level' . $childLevel . '">';
                $html .= $this->_getHtmlCat($children, $childrenWrapClass, $arrCat, $storeId);
                $html .= '</ul>';
            }
            $html .='</div>';
            $html .= '</li>';
            $counter++;
        }

        return $html;
    }

    protected function _getRenderedMenuItemAttributes($item) {
        $html = '';
        $attributes = $this->_getMenuItemAttributes($item);

        foreach ($attributes as $attributeName => $attributeValue) {
            $html .= ' ' . $attributeName . '="' . str_replace('"', '\"', $attributeValue) . '"';
        }

        return $html;
    }

    /**
     * Returns array of menu item's attributes
     *
     * @param Varien_Data_Tree_Node $item
     * @return array
     */
    protected function _getMenuItemAttributes($item) {
        $menuItemClasses = $this->_getMenuItemClasses($item);
        $attributes = array(
            'class' => implode(' ', $menuItemClasses)
        );

        return $attributes;
    }

    protected function _getMenuItemClasses($item) {
        $classes = array();

        $classes[] = ' offinews-tree-node';


        if (isset($item['Active']) && $item['Active']) {
            $classes[] = 'active';
        }

        if (!empty($item['child'])) {
            $classes[] = 'parent';
        }
        return $classes;
    }

    protected function _sortCatBlog($data, $parent_id, &$sortData) {
        foreach ($data as $listitem) {
            if ($listitem['parent_id'] == $parent_id) {

                $sortData[] = $listitem;
                $this->_sortCatBlog($data, $listitem['cat_id'], $sortData);
            }
        }
        return;
    }

}
