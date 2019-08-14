<?php

class Officience_News_Block_Menu extends Officience_News_Block_Abstract {

    public function getMenu() {
        $data = $this->getChildCatBlog(0);
        $childrenWrapClass = '';
        $html = $this->_getHtml($data, $childrenWrapClass);
        return $html;
    }

    protected function _sortCatBlog($data, $parent_id, &$sortData) {
        foreach ($data as $listitem) {
            if ($listitem['parent_id'] == $parent_id) {

                $sortData[] = $listitem;
                $this->_sortCatBlog($data, $listitem['category_id'], $sortData);
            }
        }
        return;
    }

    protected function getChildCatBlog($parentId) {
        $storeId = Mage::app()->getStore()->getId();
        $data = Mage::getModel('offinews/category')->getCollection()
                ->addFieldToFilter('parent_id', array('eq' => $parentId))
                ->addStoreFilter($storeId)
                ->addStoreEnableFilter(1)
                ->setOrder('sort_order', 'ASC')
        ;
        foreach ($data as &$items) {
            $items->setUrl($items->getUrl());
            $items->setChild($this->getChildCatBlog($items->getId()));
        }
        return $data;
    }

    protected function _getHtml($menuTree, $childrenWrapClass) {

        $html = '';
        $counter = 1;
        foreach ($menuTree as $node) {

            $childrenCount = count($menuTree);
            $children = $node['child'];
            $node['First'] = ($counter == 1);
            $node['Last'] = ($counter == $childrenCount);
            $parentLevel = $node['level'];
            $childLevel = (is_null($parentLevel) || $parentLevel == 0) ? 0 : $parentLevel;

            //$parentPositionClass = $menuTree->getPositionClass();
            //$itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($node) . ' >';
            $html .= '<a href="' . $node['url'] . '" ><span>'
                    . $this->escapeHtml($node['title']) . '</span></a>';

            if (!empty($children)) {
                if (!empty($childrenWrapClass)) {
                    $html .= '<div class="' . $childrenWrapClass . '">';
                }
                $html .= '<ul class="level' . $childLevel . '">';
                $html .= $this->_getHtml($children, $childrenWrapClass);
                $html .= '</ul>';

                if (!empty($childrenWrapClass)) {
                    $html .= '</div>';
                }
            }
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

    /**
     * Returns array of menu item's classes
     *
     * @param Varien_Data_Tree_Node $item
     * @return array
     */
    protected function _getMenuItemClasses($item) {
        $classes = array();

        $classes[] = 'level' . $item['level'];

        if ($item['First']) {
            $classes[] = 'first';
        }

        if (isset($item['Active']) && $item['Active']) {
            $classes[] = 'active';
        }

        if ($item['Last']) {
            $classes[] = 'last';
        }

        if (!empty($item['child'])) {
            $classes[] = 'parent';
        }
        return $classes;
    }

}

?>