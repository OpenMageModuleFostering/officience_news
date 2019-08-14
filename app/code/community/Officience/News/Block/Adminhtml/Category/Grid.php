<?php

class Officience_News_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('categoryGrid');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('offinews/category')->getCollection();
        $collection->getSelect()->order('sort_order ASC');
        $sortData = array();
        $this->_sortCatBlog($collection->getData(), 0, $sortData);
        $data = new Varien_Data_Collection();
        foreach ($sortData as $item) {
            $rowObj = new Varien_Object();
            $rowObj->setData($item);
            $data->addItem($rowObj);
        }
        $this->setCollection($data);
        return parent::_prepareCollection();
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

    protected function _prepareColumns() {
        $this->addColumn('category_id', array(
            'header' => Mage::helper('offinews')->__('ID'),
            'align' => 'right',
            'width' => '50',
            'index' => 'category_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('offinews')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'renderer' => 'offinews/adminhtml_category_grid_column_renderer_subCategories',
        ));

        $this->addColumn('identifier', array(
            'header' => Mage::helper('offinews')->__('URL Key'),
            'align' => 'left',
            'index' => 'identifier',
        ));

        $this->addColumn('order', array(
            'header' => Mage::helper('offinews')->__('Sort Order'),
            'align' => 'left',
            'width' => '50',
            'index' => 'order',
        ));
        
         $this->addColumn('category_status', array(
            'header'    => Mage::helper('offinews')->__('Status'),
            'align'     => 'left',
            'width'     => '70',
            'index'     => 'category_status',
            'type'      => 'options',
            'options'   => array(
                1 => Mage::helper('offinews')->__('Enabled'),
                2 => Mage::helper('offinews')->__('Disabled')
            ),
        ));


        $this->addColumn('action', array(
            'header' => Mage::helper('offinews')->__('Action'),
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('offinews')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                ),
                array(
                    'caption' => Mage::helper('offinews')->__('Add Sub Category'),
                    'url' => array('base' => '*/*/new'),
                    'field' => 'parent_id'
                )
            ),
            'width' => '70',
            'index' => 'stores',
            'is_system' => true,
            'filter' => false,
            'sortable' => false,
            'renderer' => 'offinews/adminhtml_category_grid_column_renderer_action',
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('category_id');
        $this->getMassactionBlock()->setFormFieldName('category');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('offinews')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('offinews')->__('Are you sure?')
        ));

        return $this;
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getCategoryId()));
    }

}
