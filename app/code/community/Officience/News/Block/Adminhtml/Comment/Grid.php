<?php

class Officience_News_Block_Adminhtml_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('commentGrid');
        $this->setDefaultSort('comment_status');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('offinews/comment_collection');
        if ($this->getRequest()->getParam('news_id')) {
            $collection->addNewsFilter($this->getRequest()->getParam('news_id'));
        } else {
            $tableName = Mage::getSingleton('core/resource')->getTableName('officience_news');
            $collection->getSelect()->joinLeft($tableName, 'main_table.news_id = ' . $tableName . '.news_id', array($tableName . '.title as title'));
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('comment', array(
            'header' => Mage::helper('offinews')->__('Comment'),
            'align' => 'left',
            'index' => 'comment',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('offinews')->__('News Name'),
            'index' => 'title',
        ));

        $this->addColumn('user', array(
            'header' => Mage::helper('offinews')->__('User'),
            'index' => 'user',
        ));

        $this->addColumn('email', array(
            'header' => Mage::helper('offinews')->__('E-mail'),
            'index' => 'email',
        ));

        $this->addColumn('created_time', array(
            'header' => Mage::helper('offinews')->__('Created'),
            'align' => 'center',
            'width' => '120px',
            'type' => 'date',
            'default' => '--',
            'index' => 'created_time',
        ));

        $this->addColumn('comment_status', array(
            'header' => Mage::helper('offinews')->__('Status'),
            'align' => 'center',
            'width' => '80px',
            'index' => 'comment_status',
            'type' => 'options',
            'options' => array(
                Officience_News_Helper_Data::UNAPPROVED_STATUS => 'Unapproved',
                Officience_News_Helper_Data::APPROVED_STATUS => 'Approved',
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('offinews')->__('Action'),
            'width' => '50',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('offinews')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('post_id');
        $this->getMassactionBlock()->setFormFieldName('comments');

        $this->getMassactionBlock()->addItem('approve', array(
            'label' => Mage::helper('offinews')->__('Approve'),
            'url' => $this->getUrl('*/*/massApprove'),
            'confirm' => Mage::helper('offinews')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('unapprove', array(
            'label' => Mage::helper('offinews')->__('Not Approve'),
            'url' => $this->getUrl('*/*/massUnapprove'),
            'confirm' => Mage::helper('offinews')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('offinews')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('offinews')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
