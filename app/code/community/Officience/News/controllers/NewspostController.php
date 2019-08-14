<?php

class Officience_News_NewspostController extends Mage_Core_Controller_Front_Action {

    public function viewAction() {


        $session = Mage::getSingleton('core/session');

        $identifier = $this->getRequest()->getParam('key');
        $storeId = Mage::app()->getStore()->getStoreId();
        if (preg_match('/[A-Za-z0-9\-\_]+/', $identifier)) {
            $newspost = Mage::getModel('offinews/news')->getCollection()
                    ->addEnableFilter(1)
                    ->addFilterByIdentifier($identifier)
                    ->addStoreFilter($storeId)
                    ->getFirstItem()
            ;
            Mage::register('newspost', $newspost);
            if (!$newspost->getData() && !$newspost->getData('news_status') == 1) {
                $this->_forward('NoRoute');
                return;
            }
        }
        $mode = $this->getRequest()->getParam('mode');
        if ($mode == 'print') {
            $this->_forward('print');
            return;
        }

        $newspost = Mage::registry('newspost');
        if (!$newspost) {
            $this->_forward('NoRoute');
        }

        if ($data = $this->getRequest()->getPost()) {

            if (!$newspost->getCommentsEnabled()) {
                $session->addError(Mage::helper('offinews')->__('Comments are not enabled.'));
                $this->_forward('NoRoute');
                return;
            }
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $data['user'] = (Mage::helper('offinews')->getUserName());
                $data['email'] = Mage::helper('offinews')->getUserEmail();
            }
            $data['created_time'] = now();
            $data['comment'] = htmlspecialchars($data['comment'], ENT_QUOTES);
            if ($data['comment']) {
                try {
                    if ((int) Mage::getStoreConfig('offinews/comments/need_confirmation')) {
                        $data['comment_status'] = Officience_News_Helper_Data::UNAPPROVED_STATUS;
                        $session->addSuccess(Mage::helper('offinews')->__('Your comment has been successfully sent. It will be added after approval by our admin'));
                    } else {
                        $data['comment_status'] = Officience_News_Helper_Data::APPROVED_STATUS;
                        $session->addSuccess(Mage::helper('offinews')->__('Thank you for adding a comment.'));
                    }
                    $modelComment = Mage::getModel('offinews/comment');
                    $modelComment->setData($data);
                    $modelComment->save();
                } catch (Exception $e) {
                }
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function printAction() {
        $storeId = Mage::app()->getStore()->getStoreId();
        $head = $this->getLayout()->getBlock('head');
        $identifier = $this->getRequest()->getParam('key');
        if ($identifier && !Mage::registry('news')) {
            $printPost = Mage::getModel('offinews/news')->getCollection()
                    ->addEnableFilter(1)
                    ->addFilterByIdentifier($identifier)
                    ->addStoreFilter($storeId)
                    ->getFirstItem()
            ;
            if ($printPost != null) {
                if (!Mage::registry('newspost'))
                    Mage::register('newspost', $printPost);
            } else {
                $this->_redirect(Mage::helper('offinews')->getRoute());
            }
        }
        $block = $this->getLayout()->createBlock('offinews/newspost')->setTemplate('officience/news/print.phtml');
        echo $block->toHtml();
    }

    private function checkUrlOption() {
        $urlOption = Mage::helper('offinews')->getUrloption();
        if ($urlOption == 2) {
            return true;
        }
        return false;
    }

}
