<?php

class Officience_News_Adminhtml_NewsController extends Mage_Adminhtml_Controller_Action {

    public function preDispatch() {
        parent::preDispatch();
    }

    /**
     * Init actions
     *
     */
    protected function _initAction() {
// load layout, set active menu
        $this->loadLayout()
                ->_setActiveMenu('offinews/items');
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->_addContent($this->getLayout()->createBlock('offinews/adminhtml_news'))
                ->renderLayout();
    }

    public function newAction() {

        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');

//$model = Mage::getModel('offinews/news')->load($id);
        if ($id) {
            $storeId = $this->getRequest()->getParam('store', 0);
            if ($storeId != 0) {
                $tbNewsStore = Mage::getSingleton('core/resource')->getTablename('officience_news_store');
                $model = Mage::getModel('offinews/news')->getCollection();
                $model->addFieldToFilter('main_table.news_id', array('eq' => $id));
                $model->addFieldToSelect('news_id');
                $model->getSelect()->join(array('t2' => $tbNewsStore), 'main_table.news_id = t2.news_id and t2.store_id=' . $storeId, array('t2.title',
                    't2.identifier', 't2.description', 't2.full_content', 't2.news_status', 't2.publicate_from_date',
                    't2.publicate_to_date', 't2.publicate_from_time', 't2.publicate_to_time', 't2.author', 't2.meta_keywords',
                    't2.meta_description', 't2.comments_enabled', 't2.tags', 't2.sort_order', 't2.default_value'));
                $firstData = $model->getFirstItem();


                $modelGr = Mage::getModel('offinews/news')->load($id);
                if (!$firstData->getData()) {
                    $firstData = $modelGr;
                    $firstData->setData('default_value', Mage::helper('offinews')->listDefaultNews());
                    try {
                        Mage::getModel('offinews/newsStore')->setData($firstData->getData())->save();
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
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

                if (!empty($data)) {
                    $model->getFirstItem()->setData($data);
                }

                Mage::register('news_data', $firstData);
            } else {
                $model = Mage::getModel('offinews/news');
                $model->load($id);

                if (!$model->getId()) {
                    Mage::getSingleton('adminhtml/session')
                            ->addError(Mage::helper('offinews')->__('News article does not exist'));
                    $this->_redirect('*/*/');
                    return;
                }
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

                if (!empty($data)) {
                    $model->setData($data);
                }
                Mage::register('news_data', $model);
            }
        }





        $this->_initAction()
                ->_addContent($this->getLayout()->createBlock('offinews/adminhtml_news_edit'))
                ->_addLeft($this->getLayout()->createBlock('offinews/adminhtml_news_edit_tabs'))
                ->renderLayout();
    }

    public function saveAction() {
        if ($this->getRequest()->getPost()) {

            $dataCur = array();
            $id = $this->getRequest()->getPost('news_id');
            $store = intval($this->getRequest()->getParam('store', 0));


            if ($store != 0 && $id) {

                $datatemp = $this->getRequest()->getPost();

                $newsCollection = Mage::getModel('offinews/newsStore')->getCollection()
                        ->addFieldToFilter('identifier', $datatemp['identifier']);

                $arr = $newsCollection->getData();
                if (isset($arr[0]) && $this->getRequest()->getParam('id') == $arr[0]['news_id'] && $datatemp['identifier'] == $arr[0]['identifier']) {
                    $sameUrl = null;
                } else {
                    $sameUrl = $newsCollection->getData();
                }
                if ($sameUrl != null) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('offinews')->__('News Item with such identifier already exists'));
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                }

                $str = Mage::getModel('offinews/news')->getDefaultValue($datatemp['news_id'], $store);
                $arrStr = Mage::helper('offinews')->getDefaultValueData($str);
                foreach ($arrStr as $key1 => $value1) {
                    if (isset($datatemp['default_value'][$key1])) {
                        $datatemp['default_value'][$key1] = $value1;
                    } else {
                        $datatemp['default_value'][$key1] = 0;
                    }
                }
                /*
                  if (isset($datatemp['publicate_from_time'])) {
                  $timeFrom = implode(',', $this->getRequest()->getParam('publicate_from_time'));
                  $datatemp['publicate_from_time'] = $timeFrom;
                  }
                  if (isset($datatemp['publicate_to_time'])) {
                  $timeTo = implode(',', $this->getRequest()->getParam('publicate_to_time'));
                  $datatemp['publicate_to_time'] = $timeTo;
                  }

                 */

                $dataCur = Mage::getModel('offinews/news')->load($id)->getData();
                foreach ($dataCur as $key => $value) {
                    if (isset($datatemp[$key])) {
                        $dataCur[$key] = $datatemp[$key];
                    }
                }
                $dataCur['store_id'] = $store;
                unset($dataCur['thumbnail']);

                $dataCur['default_value'] = Mage::helper('offinews')->renderDefaultToText($datatemp);
                Mage::getModel('offinews/newsStore')->deleteNewsStore($id, $store);





                $modelNewsStore = Mage::getModel('offinews/newsStore')->setData($dataCur);
                try {
                    $modelNewsStore->save();

                    Mage::getSingleton('adminhtml/session')
                            ->addSuccess(Mage::helper('offinews')->__('News was successfully saved'));
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
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('news_id')));
                    return;
                }
            } else {
                $data = $this->getRequest()->getPost();
                $newsCollection = Mage::getModel('offinews/news')->getCollection()
                        ->addFieldToFilter('identifier', $data['identifier']);
                $arr = array();
                $arr = $newsCollection->getData();
                if (isset($arr[0]) && $this->getRequest()->getParam('id') == $arr[0]['news_id'] && $data['identifier'] == $arr[0]['identifier']) {
                    $sameUrl = null;
                } else {
                    $sameUrl = $newsCollection->getData();
                }
                if ($sameUrl != null) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('offinews')->__('News Item with such identifier already exists'));
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                } else {

                    if (isset($data['is_delete'])) {
                        $isDeleteFile = true;
                    } else {
                        $isDeleteFile = false;
                    }



                    if (isset($_FILES['thumbnail']['name']) && ($_FILES['thumbnail']['name'] != '') && ($_FILES['thumbnail']['size'] != 0)) {
                        try {
                            $uploader = new Varien_File_Uploader('thumbnail');
                            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                            $uploader->setAllowRenameFiles(false);
                            $uploader->setFilesDispersion(false);
                            $path = Mage::getBaseDir('media') . DS . $this->ImageUrlSave();
                            $prefix = time() . rand();
                            $fileName = $prefix . '.' . pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
                            $uploader->save($path, $fileName);
                            $filepath = $this->ImageUrlSave() . DS . $fileName;
                            $data['thumbnail'] = $filepath;
                            $data['thumbnail'] = str_replace('\\', '/', $data['thumbnail']);
                        } catch (Exception $e) {
                            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                            return;
                        }
                    } elseif (isset($data['thumbnail']['delete'])) {
                        $path = Mage::getBaseDir('media') . DS;
                        $result = unlink($path . $data['thumbnail']['value']);
                        $data['thumbnail'] = '';
                    } else {
                        if (isset($data['thumbnail']['value'])) {
                            $data['thumbnail'] = $data['thumbnail']['value'];
                        }
                    }

                    $model = Mage::getModel('offinews/news');
                    /*
                    $dateFrom = $this->getRequest()->getParam('publicate_from_date');
                    $dateTo = $this->getRequest()->getParam('publicate_to_date');
                    $timeFrom = implode(',', $this->getRequest()->getParam('publicate_from_time'));
                    $timeTo = implode(',', $this->getRequest()->getParam('publicate_to_time'));

                    $data['publicate_from_time'] = $timeFrom;
                    $data['publicate_to_time'] = $timeTo;
                     * 
                     */
                    $data['tags'] = $this->getRequest()->getParam('tags');

// prepare dates
//                    
//                    $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
//                    if ($dateFrom != '') {
//                        if (!Zend_Date::isDate($dateFrom, $dateFormatIso)) {
//                            throw new Exception($this->__(('News date field is required')));
//                        }
//                    } else {
//                        $data['publicate_from_date'] = new Zend_Db_Expr('null');
//                    }
//
//
//                    if ($dateTo != '') {
//                        if (!Zend_Date::isDate($dateTo . ' ' . $timeTo, $dateFormatIso)) {
//                            throw new Exception($this->__(('News date field is required')));
//                        }
//                    } else {
//                        $data['publicate_to_date'] = new Zend_Db_Expr('null');
//                    }
//                    if ($id) {
//                        $data['update_time'] = now();
//                    } else {
//                        $data['created_time'] = now();
//                        $data['update_time'] = now();
//                    }

                    if ($this->getRequest()->getParam('author') == NULL) {
                        $model->setUpdateAuthor(NULL);
                    } else {
                        $model->setUpdateAuthor(Mage::getSingleton('admin/session')->getUser()->getFirstname() .
                                " " . Mage::getSingleton('admin/session')->getUser()->getLastname());
                    }

                    $model->setData($data);

                    try {


                        $model->save();

                        Mage::getSingleton('adminhtml/session')
                                ->addSuccess(Mage::helper('offinews')->__('News article has been successfully saved'));
                        Mage::getSingleton('adminhtml/session')->setFormData(false);

                        if ($this->getRequest()->getParam('back')) {
                            $this->_redirect('*/*/edit', array('id' => $model->getId()));
                            return;
                        }
                        $this->_redirect('*/*/');
                        return;
                    } catch (Exception $e) {
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        Mage::getSingleton('adminhtml/session')->setFormData($data);
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;
                    }
                }

                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('offinews')->__('No items to save'));
                $this->_redirect('*/*/');
            }
        }
    }

    protected function ImageUrlSave() {
        return 'Officience/News/Post/Thumbnail';
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('offinews/news');
                $model->load($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')
                        ->addSuccess(Mage::helper('adminhtml')->__('Item has been successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $newsIds = $this->getRequest()->getParam('offinews');
        if (!is_array($newsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                $model = Mage::getModel('offinews/news');
                foreach ($newsIds as $newsId) {
                    $model->reset()
                            ->load($newsId)
                            ->delete();
                }
                Mage::getSingleton('adminhtml/session')
                        ->addSuccess(Mage::helper('adminhtml')
                                ->__('%d record(s) have been successfully deleted', count($newsIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'news.csv';
        $grid = $this->getLayout()
                        ->createBlock('offinews/adminhtml_news_grid')
                        ->addColumn('news_id', array(
                            'header' => Mage::helper('offinews')->__('ID'),
                            'align' => 'right',
                            'width' => '50',
                            'index' => 'news_id',
                        ))->addColumn('title', array(
                    'header' => Mage::helper('offinews')->__('Title'),
                    'align' => 'left',
                    'index' => 'title',
                ))->addColumn('identifier', array(
                    'header' => Mage::helper('offinews')->__('URL Key'),
                    'align' => 'left',
                    'index' => 'identifier',
                ))->addColumn('author', array(
                    'header' => Mage::helper('offinews')->__('Author'),
                    'index' => 'author',
                ))->addColumn('short_content', array(
                    'header' => Mage::helper('offinews')->__('Short Content'),
                    'type' => 'text',
                    'index' => 'short_content',
                ))->addColumn('image_short_content', array(
                    'header' => Mage::helper('offinews')->__('Short Content Image'),
                    'type' => 'text',
                    'display' => 'none',
                    'index' => 'image_short_content',
                ))->addColumn('full_content', array(
                    'header' => Mage::helper('offinews')->__('Full Content'),
                    'type' => 'text',
                    'index' => 'full_content',
                ))->addColumn('image_full_content', array(
                    'header' => Mage::helper('offinews')->__('Full Content Image'),
                    'type' => 'text',
                    'index' => 'image_full_content',
                ))->addColumn('document', array(
                    'header' => Mage::helper('offinews')->__('Document'),
                    'type' => 'text',
                    'index' => 'document',
                ))->addColumn('meta_keywords', array(
                    'header' => Mage::helper('offinews')->__('Meta Keywords'),
                    'type' => 'text',
                    'index' => 'meta_keywords',
                ))->addColumn('meta_description', array(
                    'header' => Mage::helper('offinews')->__('Meta Description'),
                    'type' => 'text',
                    'index' => 'meta_description',
                ))->addColumn('tags', array(
            'header' => Mage::helper('offinews')->__('Tags'),
            'type' => 'text',
            'index' => 'tags',
        ));

        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    public function massStatusAction() {
        $newsIds = $this->getRequest()->getParam('news');
        if (!is_array($newsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($newsIds as $newsId) {
                    $model = Mage::getSingleton('offinews/news')
                            ->setId($newsId)
                            ->setStatus($this->getRequest()->getParam('news_status'))
                            ->save();
                }
                $this->_getSession()
                        ->addSuccess($this->__('%d record(s) have been successfully updated', count($newsIds)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}
