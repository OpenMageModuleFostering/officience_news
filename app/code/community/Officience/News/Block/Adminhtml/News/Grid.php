<?php

class Officience_News_Block_Adminhtml_News_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('newsGrid');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('offinews/news')->getCollection();
//        foreach($collection as $item) {
//            $stores = $this->lookupStoreIds($item->getId());
//            $item->setData('store_id', $stores);
//        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('news_id', array(
            'header'    => Mage::helper('offinews')->__('ID'),
            'align'     =>'right',
            'width'     => '50',
            'index'     => 'news_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('offinews')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));

        $this->addColumn('identifier', array(
            'header'    => Mage::helper('offinews')->__('URL Key'),
            'align'     => 'left',
            'index'     => 'identifier',
        ));

        $this->addColumn('author', array(
            'header'    => Mage::helper('offinews')->__('Author'),
            'index'     => 'author',
        ));


        $this->addColumn('created_time', array(
            'header'    => Mage::helper('offinews')->__('Created'),
            'align'     => 'left',
            'width'     => '100',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'created_time',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('offinews')->__('Updated'),
            'align'     => 'left',
            'width'     => '100',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'update_time',
        ));

        $this->addColumn('news_status', array(
            'header'    => Mage::helper('offinews')->__('Status'),
            'align'     => 'left',
            'width'     => '70',
            'index'     => 'news_status',
            'type'      => 'options',
            'options'   => array(
                1 => Mage::helper('offinews')->__('Enabled'),
                2 => Mage::helper('offinews')->__('Disabled')
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('offinews')->__('Action'),
                'width'     => '60',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('offinews')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        $this->addColumn('view_comments',
            array(
                'header'    =>  Mage::helper('offinews')->__('Comments'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('offinews')->__('View comments'),
                        'url'       => array('base'=> 'offinews/adminhtml_comment/index'),
                        'field'     => 'news_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('news_id');
        $this->getMassactionBlock()->setFormFieldName('news');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('offinews')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('offinews')->__('Are you sure?')
        ));

        $statuses = array(
              1 => Mage::helper('offinews')->__('Enabled'),
              0 => Mage::helper('offinews')->__('Disabled')
        );
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('news_status', array(
             'label'=> Mage::helper('offinews')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'news_status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('offinews')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function lookupStoreIds($objectId)
    {
        $adapter = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tableName = Mage::getSingleton('core/resource')->getTableName('officience_news_store');
        $select  = $adapter->select()
            ->from($tableName, 'store_id')
            ->where('news_id = ?',(int)$objectId);

        return $adapter->fetchCol($select);
    }
}
