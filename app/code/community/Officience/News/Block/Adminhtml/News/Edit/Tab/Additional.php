<?php

class Officience_News_Block_Adminhtml_News_Edit_Tab_Additional extends Mage_Adminhtml_Block_Widget_Form {

    public function initForm() {
        $form = new Varien_Data_Form();
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        $data = Mage::registry('news_data');
        $fieldset = $form->addFieldset('news_meta_data', array('legend' => Mage::helper('offinews')->__('Meta Data')));

        if ($storeId) {


            $arrMetaKeywords = array(
                'name' => 'meta_keywords',
                'label' => Mage::helper('offinews')->__('Keywords'),
                'title' => Mage::helper('offinews')->__('Meta Keywords'),
                'style' => 'width: 520px;',
            );
            $fieldset->addField('meta_keywords', 'editor', $arrMetaKeywords)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('meta_keywords', $defaultValueArr['meta_keywords']));

            $arrMetaDescription = array(
                'name' => 'meta_description',
                'label' => Mage::helper('offinews')->__('Description'),
                'title' => Mage::helper('offinews')->__('Meta Description'),
                'style' => 'width: 520px;',
            );
            $fieldset->addField('meta_description', 'editor', $arrMetaDescription)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('meta_description', $defaultValueArr['meta_description']));

            $fieldset = $form->addFieldset('news_options_data', array('legend' => Mage::helper('offinews')->__('Advanced Post Options')));

            $arrAuthor = array(
                'label' => Mage::helper('offinews')->__('Author name'),
                'name' => 'author',
                'style' => 'width: 520px;',
                'after_element_html' => '<span class="hint"><p class="note">' . $this->__('Leave blank to disable') . '</p></span>',
            );
            $fieldset->addField('author', 'text', $arrAuthor)
                ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('author', $defaultValueArr['author']));
        } else {

            $fieldset->addField('meta_keywords', 'editor', array(
                'name' => 'meta_keywords',
                'label' => Mage::helper('offinews')->__('Keywords'),
                'title' => Mage::helper('offinews')->__('Meta Keywords'),
                'style' => 'width: 520px;',
            ));

            $fieldset->addField('meta_description', 'editor', array(
                'name' => 'meta_description',
                'label' => Mage::helper('offinews')->__('Description'),
                'title' => Mage::helper('offinews')->__('Meta Description'),
                'style' => 'width: 520px;',
            ));

            $fieldset = $form->addFieldset('news_options_data', array('legend' => Mage::helper('offinews')->__('Advanced Post Options')));

            $fieldset->addField('author', 'text', array(
                'label' => Mage::helper('offinews')->__('Author name'),
                'name' => 'author',
                'style' => 'width: 520px;',
                'after_element_html' => '<span class="hint"><p class="note">' . $this->__('Leave blank to disable') . '</p></span>',
            ));
        }

        if (Mage::getSingleton('adminhtml/session')->getNewsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getNewsData());
            Mage::getSingleton('adminhtml/session')->setNewsData(null);
        } elseif (Mage::registry('news_data')) {
            $form->setValues(Mage::registry('news_data')->getData());
        }
        $this->setForm($form);
        return $this;
    }

}
