<?php

class Officience_News_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    public function initForm() {

        $model = Mage::registry('cat_data');
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('news_form', array(
            'legend' => $this->__('Category Information'),
        ));
        if ($storeId) {
            $defaultValue = $model->getData();
            $defaultValueArr = $defaultValue['default_value'];
            /* ==Title== */
            $arrTitle = array(
                'label' => Mage::helper('offinews')->__('Title'),
                'title' => Mage::helper('offinews')->__('Title'),
                'name' => 'title',
                'required' => true,
            );

            if (isset($defaultValueArr['title']) && $defaultValueArr['title']) {
                $arrTitle['disabled'] = ' ';
                $arrTitle['class'] = 'disabled';
            }
            $fieldset->addField('title', 'text', $arrTitle)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('title', $defaultValueArr['title']));
            /* ==End Title== */
            /* ==Identifier== */
            $arrIdentifier = array(
                'label' => Mage::helper('offinews')->__('URL Key'),
                'title' => Mage::helper('offinews')->__('URL Key'),
                'name' => 'identifier',
            );
            if (isset($defaultValueArr['identifier']) && $defaultValueArr['identifier']) {
                $arrIdentifier['disabled'] = ' ';
                $arrIdentifier['class'] = 'disabled';
            }
            $fieldset->addField('identifier', 'text', $arrIdentifier)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('identifier', $defaultValueArr['identifier']));
            /* ==End Identifier== */
            /* Status */
            $arrStatus = array(
                'label' => Mage::helper('offinews')->__('Status'),
                'title' => Mage::helper('offinews')->__('Status'),
                'name' => 'category_status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('offinews')->__('Enabled'),
                    ),
                    array(
                        'value' => 2,
                        'label' => Mage::helper('offinews')->__('Disabled'),
                    ),
                ),
            );
            $fieldset->addField('category_status', 'select', $arrStatus)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('category_status', $defaultValueArr['category_status']));
            /* End status */
            /* display setting */
            $arrStatus = array(
                'label' => Mage::helper('offinews')->__('Display setting'),
                'title' => Mage::helper('offinews')->__('Display setting'),
                'name' => 'display_setting',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('offinews')->__('Display categories'),
                    ),
                    array(
                        'value' => 2,
                        'label' => Mage::helper('offinews')->__('Display products'),
                    ),
                ),
            );
            $fieldset->addField('display_setting', 'select', $arrStatus)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('display_setting', $defaultValueArr['display_setting']));
            /* End display setting */
            /* ==Order== */
            $arrOrder = array(
                'label' => Mage::helper('offinews')->__('Order'),
                'title' => Mage::helper('offinews')->__('Order'),
                'name' => 'sort_order',
            );

            if (isset($defaultValueArr['sort_order']) && $defaultValueArr['sort_order']) {
                $arrOrder['disabled'] = ' ';
                $arrOrder['class'] = 'disabled';
            }


            $fieldset->addField('sort_order', 'text', $arrOrder)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('sort_order', $defaultValueArr['sort_order']));
            /* ==End Order== */
            /* ==description== */
            $arrDescription = array(
                'label' => Mage::helper('offinews')->__('Description'),
                'title' => Mage::helper('offinews')->__('Description'),
                'name' => 'description',
            );
            if (isset($defaultValueArr['description']) && $defaultValueArr['description']) {
                $arrDescription['disabled'] = ' ';
                $arrDescription['class'] = 'disabled';
            }
            $fieldset->addField('description', 'textarea', $arrDescription)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('description', $defaultValueArr['description']));
            /* ==End description== */
        } else {
            $fieldset->addField('title', 'text', array(
                'label' => Mage::helper('offinews')->__('Title'),
                'title' => Mage::helper('offinews')->__('Title'),
                'name' => 'title',
                'required' => true,
            ));

            $fieldset->addField('identifier', 'text', array(
                'label' => Mage::helper('offinews')->__('URL Key'),
                'title' => Mage::helper('offinews')->__('URL Key'),
                'name' => 'identifier',
            ));

            $fieldset->addField('category_status', 'select', array(
                'label' => Mage::helper('offinews')->__('Status'),
                'title' => Mage::helper('offinews')->__('Status'),
                'name' => 'category_status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('offinews')->__('Enabled'),
                    ),
                    array(
                        'value' => 2,
                        'label' => Mage::helper('offinews')->__('Disabled'),
                    ),
                ),
            ));
            
            $fieldset->addField('display_setting', 'select', array(
                'label' => Mage::helper('offinews')->__('Display setting'),
                'title' => Mage::helper('offinews')->__('Display setting'),
                'name' => 'display_setting',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('offinews')->__('Display categories'),
                    ),
                    array(
                        'value' => 2,
                        'label' => Mage::helper('offinews')->__('Display products'),
                    ),
                ),
            ));

            $fieldset->addField('thumbnail', 'image', array(
                'label' => Mage::helper('offinews')->__('Thumbnail'),
                'title' => Mage::helper('offinews')->__('Thumbnail'),
                'name' => 'thumbnail',
            ));

            $fieldset->addField('sort_order', 'text', array(
                'label' => Mage::helper('offinews')->__('Order'),
                'title' => Mage::helper('offinews')->__('Order'),
                'name' => 'sort_order',
            ));

            $fieldset->addField('description', 'textarea', array(
                'label' => Mage::helper('offinews')->__('Description'),
                'title' => Mage::helper('offinews')->__('Description'),
                'name' => 'description',
            ));
        }

        $pid = $this->getRequest()->getParam('parent_id', 0);
        $fieldset->addField('parent_id', 'hidden', array(
            'name' => 'parent_id',
            'value' => $pid,
        ));


        $levelCat = 0;
        if ($pid != 0) {
            $category = Mage::getModel('offinews/category')->load($pid);
            $lev = $category->getLevel();
            if ($lev) {
                $levelCat = $lev + 1;
            } else {
                $levelCat = '1';
            }
        } else {
            $levelCat = '1';
        }

        $fieldset->addField('level', 'hidden', array(
            'name' => 'level',
            'value' => $levelCat,
        ));


        if ($this->getRequest()->getParam('id')) {
            $fieldset->addField('category_id', 'hidden', array(
                'name' => 'category_id',
            ));
            $form->setValues($this->getRequest()->getParam('id'));
        }
        if (Mage::getSingleton('adminhtml/session')->getNewsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getNewsData());
            Mage::getSingleton('adminhtml/session')->setNewsData(null);
        } elseif ($model) {
            $form->setValues($model->getData());
        }

        return $this;
    }

}

?>
