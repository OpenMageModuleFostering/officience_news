<?php

class Officience_News_Block_Adminhtml_Comment_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $collection = Mage::getResourceModel('offinews/comment_collection');

        $news_id = $this->getRequest()->getParam('id');
        $tableName = Mage::getSingleton('core/resource')->getTableName('officience_news');
        $collection->getSelect()->joinLeft(array("t2" => $tableName), 'main_table.news_id = t2.news_id', 'title');
        $collection->getSelect()->distinct();
        $collection->getSelect()->where('main_table.news_id =' . $news_id);
        $collection->getSelect()->limit(1);
        $data = $collection->getData();
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
        ));

        $fieldset = $form->addFieldset('comment_form', array('legend' => Mage::helper('offinews')->__('Comment Information')));


//        $fieldset->addField('title', 'hidden', array(
//            'label' => Mage::helper('offinews')->__('News Name'),
//            'after_element_html' => '<tr><td class="label"><label for="title">News Name</label></td>
//                <td class="value">' . $data[0]['title'] . '</td></tr>',
//        ));

        $fieldset->addField('user', 'text', array(
            'label' => Mage::helper('offinews')->__('User'),
            'name' => 'user',
        ));

        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('offinews')->__('Email Address'),
            'name' => 'email',
        ));

        $fieldset->addField('comment_status', 'select', array(
            'label' => Mage::helper('offinews')->__('Status'),
            'name' => 'comment_status',
            'values' => array(
                array(
                    'value' => Officience_News_Helper_Data::UNAPPROVED_STATUS,
                    'label' => Mage::helper('offinews')->__('Unapproved'),
                ),
                array(
                    'value' => Officience_News_Helper_Data::APPROVED_STATUS,
                    'label' => Mage::helper('offinews')->__('Approved'),
                ),
            ),
        ));

        $fieldset->addField('comment', 'editor', array(
            'name' => 'comment',
            'label' => Mage::helper('offinews')->__('Comment'),
            'title' => Mage::helper('offinews')->__('Comment'),
            'style' => 'width:500px; height:250px;',
            'wysiwyg' => false,
            'required' => false,
        ));

        if (Mage::getSingleton('adminhtml/session')->getNewsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getNewsData());
            Mage::getSingleton('adminhtml/session')->setNewsData(null);
        } elseif (Mage::registry('offi_comment_data')) {
            $form->setValues(Mage::registry('offi_comment_data')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
