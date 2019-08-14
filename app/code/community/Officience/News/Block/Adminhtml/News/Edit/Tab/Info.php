<?php

class Officience_News_Block_Adminhtml_News_Edit_Tab_Info extends Mage_Adminhtml_Block_Widget_Form {

    public function initForm() {

        $data = Mage::registry('news_data');
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('news_form', array('legend' => Mage::helper('offinews')->__('News information'),
        ));

        if ($storeId) {
            /* Title */
            $arrTitle = array(
                'label' => Mage::helper('offinews')->__('Title'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'title',
            );
            $fieldset->addField('title', 'text', $arrTitle);
            /* End Title */
            /* identifier */
            $arrIdentifier = array(
                'label' => Mage::helper('offinews')->__('Identifier'),
                'title' => Mage::helper('offinews')->__('Identifier'),
                'class' => 'required-entry',
                'required' => false,
                'name' => 'identifier',
                'class' => 'validate-identifier',
                'after_element_html' => '<div class="hint"><p class="note">' . $this->__('e.g. domain.com/news/identifier') . '</p></div>',
            );
            $fieldset->addField('identifier', 'text', $arrIdentifier)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('identifier', $defaultValueArr['identifier']));

            /* End identifier */
            /* status */
            $arrStatus = array(
                'label' => Mage::helper('offinews')->__('Status'),
                'name' => 'news_status',
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
            $fieldset->addField('news_status', 'select', $arrStatus)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('news_status', $defaultValueArr['news_status']));
            /* End status */
            /* publicate_from_time */
            /*$arrPublicateFromDate = array(
                'label' => $this->__('publicate_from_date'),
                'name' => 'publicate_from_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            );
            $fieldset->addField('publicate_from_date', 'date', $arrPublicateFromDate)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('publicate_from_date', $defaultValueArr['publicate_from_date']));
*/
            /* End publicate_from_time */
            /* identifier */
            /*
            $arrPublicateFromTime = array(
                'label' => Mage::helper('offinews')->__('publicate_from_time'),
                'name' => 'publicate_from_time',
                'value' => '12,04,15',
                'after_element_html' => '<small>Comments</small>',
            );
            $fieldset->addField('publicate_from_time', 'time', $arrPublicateFromTime)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('publicate_from_time', $defaultValueArr['publicate_from_time']));
            */
              /* identifier */
             
            /* publicate_to_date */
           /* $arrPublicateToDate = array(
                'label' => $this->__('publicate_to_date'),
                'name' => 'publicate_to_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
            );
            $fieldset->addField('publicate_to_date', 'date', $arrPublicateToDate)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('publicate_to_date', $defaultValueArr['publicate_to_date']));
*/
            /* End publicate_to_date */
            /* publicate_to_time *//*
            $arrPublicateToTime = array(
                'label' => Mage::helper('offinews')->__('publicate_to_time'),
                'name' => 'publicate_to_time',
                'onclick' => "",
                'onchange' => "",
                'value' => '01,00,01',
                'disabled' => false,
                'readonly' => false,
                'after_element_html' => '<small>Comments</small>',
            );
            $fieldset->addField('publicate_to_time', 'time', $arrPublicateToTime)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('publicate_to_time', $defaultValueArr['publicate_to_time']));
*/
            /* End publicate_to_time */
            /* comments_enabled */
            $arrCommentsEnabled = array(
                'label' => Mage::helper('offinews')->__('Comments'),
                'name' => 'comments_enabled',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('offinews')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('offinews')->__('Disabled'),
                    ),
                ),
            );
            $fieldset->addField('comments_enabled', 'select', $arrCommentsEnabled)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('comments_enabled', $defaultValueArr['comments_enabled']));


            /* End comments_enabled */
            /* description */
            $arrDescription = array(
                'name' => 'description',
                'label' => Mage::helper('offinews')->__('Description'),
                'title' => Mage::helper('offinews')->__('Description'),
                'style' => 'height:36em',
                'config' => Mage::getSingleton('offinews/wysiwyg_config')->getConfig(),
                'wysiwyg' => true
            );
            $fieldset->addField('description', 'editor', $arrDescription)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('description', $defaultValueArr['description']));
            /* End description */
            /* full_content */
            $arrFullContent = array(
                'name' => 'full_content',
                'label' => Mage::helper('offinews')->__('Full Description'),
                'title' => Mage::helper('offinews')->__('Full Description'),
                'style' => 'height:36em',
                'config' => Mage::getSingleton('offinews/wysiwyg_config')->getConfig(),
                'wysiwyg' => true
            );
            $fieldset->addField('full_content', 'editor', $arrFullContent)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('full_content', $defaultValueArr['full_content']));
            /* End full_content */
            /* tags */
            $arrTags = array(
                'label' => Mage::helper('offinews')->__('Add Tags'),
                'title' => Mage::helper('offinews')->__('Add Tags'),
                'name' => 'tags',
                'after_element_html' => '<div class="hint"><p class="note">' . $this->__('Use comma for multiple words') . '</p></div>',
            );
            $fieldset->addField('tags', 'text', $arrTags)
                    ->setAfterElementHtml(Mage::helper('offinews')->addAfterElement('tags', $defaultValueArr['tags']));
            /* End tags */
        } else {
            $fieldset->addField('title', 'text', array(
                'label' => Mage::helper('offinews')->__('Title'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'title',
            ));

            $fieldset->addField('identifier', 'text', array(
                'label' => Mage::helper('offinews')->__('Identifier'),
                'title' => Mage::helper('offinews')->__('Identifier'),
                'class' => 'required-entry',
                'required' => false,
                'name' => 'identifier',
                'class' => 'validate-identifier',
                'after_element_html' => '<div class="hint"><p class="note">' . $this->__('e.g. domain.com/news/identifier') . '</p></div>',
            ));

            $fieldset->addField('news_status', 'select', array(
                'label' => Mage::helper('offinews')->__('Status'),
                'name' => 'news_status',
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

/*
            $fieldset->addField('publicate_from_date', 'date', array(
                'label' => $this->__('publicate from date'),
                'name' => 'publicate_from_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            ));

            $fieldset->addField('publicate_from_time', 'time', array(
                'label' => Mage::helper('offinews')->__('publicate from time'),
                'name' => 'publicate_from_time',
                'value' => '12,04,15',
                'after_element_html' => '<small>Comments</small>',
            ));

            $fieldset->addField('publicate_to_date', 'date', array(
                'label' => $this->__('publicate to date'),
                'name' => 'publicate_to_date',
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            ));

            $fieldset->addField('publicate_to_time', 'time', array(
                'label' => Mage::helper('offinews')->__('publicate to time'),
                'name' => 'publicate_to_time',
                'onclick' => "",
                'onchange' => "",
                'value' => '01,00,01',
                'disabled' => false,
                'readonly' => false,
                'after_element_html' => '<small>Comments</small>',
            ));
*/

            $fieldset->addField('thumbnail', 'image', array(
                'label' => Mage::helper('offinews')->__('Thumnail'),
                'required' => false,
                'name' => 'thumbnail',
            ));


            $fieldset->addField('comments_enabled', 'select', array(
                'label' => Mage::helper('offinews')->__('Comments'),
                'name' => 'comments_enabled',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('offinews')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('offinews')->__('Disabled'),
                    ),
                ),
            ));



            $fieldset->addField('description', 'editor', array(
                'name' => 'description',
                'label' => Mage::helper('offinews')->__('Description'),
                'title' => Mage::helper('offinews')->__('Description'),
                'style' => 'height:36em',
                'config' => Mage::getSingleton('offinews/wysiwyg_config')->getConfig(),
                'wysiwyg' => true
            ));

            $fieldset->addField('full_content', 'editor', array(
                'name' => 'full_content',
                'label' => Mage::helper('offinews')->__('Full Description'),
                'title' => Mage::helper('offinews')->__('Full Description'),
                'style' => 'height:36em',
                'config' => Mage::getSingleton('offinews/wysiwyg_config')->getConfig(),
                'wysiwyg' => true
            ));

            $fieldset->addField('tags', 'text', array(
                'label' => Mage::helper('offinews')->__('Add Tags'),
                'title' => Mage::helper('offinews')->__('Add Tags'),
                'name' => 'tags',
                'after_element_html' => '<div class="hint"><p class="note">' . $this->__('Use comma for multiple words') . '</p></div>',
            ));
        }

        if ($this->getRequest()->getParam('id', 0)) {
            $fieldset->addField('news_id', 'hidden', array(
                'name' => 'news_id',
            ));
            $form->setValues($this->getRequest()->getParam('id'));
        }

        if (Mage::getSingleton('adminhtml/session')->getNewsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getNewsData());
            Mage::getSingleton('adminhtml/session')->setNewsData(null);
        } elseif (Mage::registry('news_data')) {
            $data = Mage::registry('news_data');
            if (($data->getImageShortContent() == $data->getImageFullContent()) || $data->getImageShortContent() == '' || !$data->getImageShortContent()) {
                $data->setUseFullImg(1);
            }
            $form->setValues($data->getData());
        }

        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }
        $this->setForm($form);
        return $this;
    }

}
