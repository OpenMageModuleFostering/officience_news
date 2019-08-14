<?php

class Officience_News_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action {

    public function preDispatch() {
        parent::preDispatch();
    }

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('offinews/category');
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->_addContent($this->getLayout()->createBlock('offinews/adminhtml_category'))
                ->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $storeId = $this->getRequest()->getParam('store', 0);

            if ($storeId != 0) {
                $tbCategoryStore = Mage::getSingleton('core/resource')->getTableName('officience_category_store');

                $model = Mage::getModel('offinews/category')->getCollection();

                $model->addFieldToFilter('main_table.category_id', array('eq' => $id));

                $model->addFieldToSelect(array('thumbnail', 'parent_id', 'path', 'level'));

                $model->getSelect()->join(array("t2" => $tbCategoryStore), 'main_table.category_id = t2.category_id and store_id=' . $storeId
                        , array('t2.title', 't2.description', 't2.identifier', 't2.sort_order', 't2.meta_keywords', 't2.meta_description', 't2.default_value'));

                $firstData = $model->getFirstItem();
                $modelGr = Mage::getModel('offinews/category')->load($id);
                if (!$firstData->getData()) {
                    $firstData = $modelGr;
                    $firstData->setData('default_value', Mage::helper('offinews')->listDefaultCat());
                    try {
                        Mage::getModel('offinews/categoryStore')->setData($firstData->getData())->save();
                    } catch (Exception $e) {
                        
                    }
                }
                $defaultValueArr = Mage::helper('offinews')->getDefaultValueData($firstData->getData());
                foreach ($defaultValueArr as $keyDf => $valueDf) {
                    if (!$valueDf == '0') {
                        $firstData->setData($keyDf, $modelGr->getData($keyDf));
                    }
                }
                $firstData->setData('default_value', $defaultValueArr);

                if (!$firstData->getId()) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('offinews')->__('Page access error'));
                    $this->_redirect('*/*/');
                    return;
                }
                Mage::register('cat_data', $firstData);
            } else {
                $model = Mage::getModel('offinews/category');
                $model->load($id);
                if (!$model->getId()) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('news')->__('Page access error'));
                    $this->_redirect('*/*/');
                    return;
                }
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if (!empty($data)) {
                    $model->setData($data);
                }

                Mage::register('cat_data', $model);
            }
        }

        $this->_initAction()
                ->_addContent($this->getLayout()->createBlock('offinews/adminhtml_category_edit'))
                ->_addLeft($this->getLayout()->createBlock('offinews/adminhtml_category_edit_tabs'));

        $this->renderLayout();
    }

    protected function ImageUrlSave() {
        return 'Officience/News/Category';
    }

    public function saveAction() {
        // check if data sent
        if ($this->getRequest()->getPost()) {
            $dataCur = array();
            $store = intval($this->getRequest()->getParam('store', 0));
            if ($store != 0 && $this->getRequest()->getPost('category_id')) {
                $datatemp = $this->getRequest()->getPost();
                if (!$datatemp['identifier']) {
                    $datatemp['identifier'] = Mage::helper('offinews')->vnFilter($datatemp['title']);
                } else {
                    $datatemp['identifier'] = Mage::helper('offinews')->vnFilter($datatemp['identifier']);
                }
                // try {
                $id = $this->getRequest()->getPost('category_id');
                $str = Mage::getModel('offinews/category')->getDefaultValue($datatemp['category_id'], $store);
                $arrStr = Mage::helper('offinews')->getDefaultValueData($str);

                foreach ($arrStr as $key1 => $value1) {
                    if (isset($datatemp['default_value'][$key1])) {
                        $datatemp['default_value'][$key1] = $value1;
                    } else {
                        $datatemp['default_value'][$key1] = 0;
                    }
                }

                $dataCur = Mage::getModel('offinews/category')->load($id)->getData();
                foreach ($dataCur as $key => $value) {
                    if (isset($datatemp[$key])) {
                        $dataCur[$key] = $datatemp[$key];
                    }
                }
                $dataCur['store_id'] = $store;
                unset($dataCur['level'], $dataCur['form_key'], $dataCur['parent_id']);

                $dataCur['default_value'] = Mage::helper('offinews')->renderDefaultToText($datatemp);
                Mage::getModel('offinews/categoryStore')->deleteCategoryStore($id, $store);

                $modelCatStore = Mage::getModel('offinews/categoryStore')->setData($dataCur);

                $modelCatStore->save();
                try {

                    Mage::getSingleton('adminhtml/session')
                            ->addSuccess(Mage::helper('offinews')->__('Category was successfully saved'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);

                    // check if 'Save and Continue'
                    if ($this->getRequest()->getParam('back')) {
                        if ($store)
                            $this->_redirect('*/*/edit', array('id' => $id, 'store' => $store));
                        else
                            $this->_redirect('*/*/edit', array('id' => $id));
                        return;
                    }
                    $this->_redirect('*/*/');
                    return;
                } catch (Exception $e) {

                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('category_id')));
                    return;
                }
//                } catch (Exception $e) {
//                    
//                }
            } else {
                $data = $this->getRequest()->getPost();
                if (isset($_FILES['thumbnail']['name']) and (file_exists($_FILES['thumbnail']['tmp_name']))) {
                    try {
                        $upload = new Varien_File_Uploader('thumbnail');
                        $upload->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                        $upload->setAllowRenameFiles(true);
                        $upload->setFilesDispersion(false);
                        $path = Mage::getBaseDir('media') . DS . $this->ImageUrlSave();
                        $upload->save($path, str_replace(' ', '', $_FILES['thumbnail']['name']));
                        $data['thumbnail'] = $this->ImageUrlSave() . '/' . str_replace(' ', '', $_FILES['thumbnail']['name']);
                    } catch (Exception $e) {
                        
                    }
                } else {
                    if (isset($data['thumbnail']['delete']) && $data['thumbnail']['delete'] == 1) {
                        $data['thumbnail'] = '';
                    } else {
                        unset($data['thumbnail']);
                    }
                }

                $identifier = '';
                if ($data['identifier'] != '') {
                    $identifier = Mage::helper('offinews')->vnFilter($data['identifier']);
                } else {
                    $identifier = Mage::helper('offinews')->vnFilter($data['title']);
                }
                $data['identifier'] = $identifier;
                $model = Mage::getModel('offinews/category');

                $model->setData($data);
                $model->save();

                try {

                    Mage::getSingleton('adminhtml/session')
                            ->addSuccess(Mage::helper('offinews')->__('Category was successfully saved'));
                    Mage::getSingleton('adminhtml/session')->setFormData(false);

                    // check if 'Save and Continue'
                    if ($this->getRequest()->getParam('back')) {
                        if ($store)
                            $this->_redirect('*/*/edit', array('id' => $model->getId(), 'store' => $store));
                        else
                            $this->_redirect('*/*/edit', array('id' => $model->getId()));
                        return;
                    }
                    $this->_redirect('*/*/');
                    return;
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('category_id')));
                    return;
                }
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('offinews/category');
                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')
                        ->addSuccess(Mage::helper('offinews')->__('Category was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $categoryIds = $this->getRequest()->getParam('category');
        if (!is_array($categoryIds)) {
            Mage::getSingleton('adminhtml/session')
                    ->addError(Mage::helper('adminhtml')->__('Please select categories'));
        } else {
            try {
                foreach ($categoryIds as $categoryId) {
                    $model = Mage::getModel('offinews/category')->load($categoryId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')
                        ->addSuccess(Mage::helper('adminhtml')
                                ->__('%d categories have been successfully deleted', count($categoryIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

}
