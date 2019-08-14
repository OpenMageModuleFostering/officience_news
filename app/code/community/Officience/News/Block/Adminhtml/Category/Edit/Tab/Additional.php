<?php

class Officience_News_Block_Adminhtml_Category_Edit_Tab_Additional extends Mage_Adminhtml_Block_Widget_Form {

    public function initForm() {
        $form = new Varien_Data_Form();
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        $model = Mage::registry('cat_data');
        $fieldset = $form->addFieldset('news_meta_data', array('legend' => Mage::helper('offinews')->__('Meta Data')));
        if ($storeId) {
            $defaultValue = $model->getData();
            $defaultValueArr = $defaultValue['default_value'];
            /* ==meta keywords== */
            $arrMetaKeywords = array(
                'label' => $this->__('Meta keyword'),
                'title' => $this->__('Meta keyword'),
                'name' => 'meta_keywords',
            );

            if (isset($defaultValueArr['title']) && $defaultValueArr['title']) {
                $arrMetaKeywords['disabled'] = ' ';
                $arrMetaKeywords['class'] = 'disabled';
            }

            $fieldset->addField('meta_keywords', 'textarea', $arrMetaKeywords)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('meta_keywords', $defaultValueArr['meta_keywords']));

            /* ==End meta keywords== */
            /* ==meta description== */
            $arrMetaDescription = array(
                'label' => $this->__('Meta description'),
                'title' => $this->__('Meta description'),
                'name' => 'meta_description',
            );

            if (isset($defaultValueArr['title']) && $defaultValueArr['title']) {
                $arrMetaDescription['disabled'] = ' ';
                $arrMetaDescription['class'] = 'disabled';
            }
            $fieldset->addField('meta_description', 'textarea', $arrMetaDescription)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('meta_description', $defaultValueArr['meta_description']));
            /* ==End meta description== */
        } else {
            $fieldset->addField('meta_keywords', 'textarea', array(
                'label' => $this->__('Meta keyword'),
                'title' => $this->__('Meta keyword'),
                'name' => 'meta_keywords',
            ));

            $fieldset->addField('meta_description', 'textarea', array(
                'label' => Mage::helper('offinews')->__('Meta description'),
                'title' => Mage::helper('offinews')->__('Meta description'),
                'name' => 'meta_description',
            ));
        }
        if (Mage::registry('cat_data')) {
            $form->setValues(Mage::registry('cat_data')->getData());
        }
        $this->setForm($form);
        return $this;
    }

}
?>

