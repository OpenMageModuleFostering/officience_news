<?php

class Officience_News_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

    public function initControllerRouters($observer) {
        $front = $observer->getEvent()->getFront();
        $news = new Officience_News_Controller_Router();
        $front->addRouter('offinews', $news);
    }

    public function match(Zend_Controller_Request_Http $request) {
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                    ->setRedirect(Mage::getUrl('install'))
                    ->sendResponse();
            exit;
        }
        $route = Mage::helper('offinews')->getRoute();
        $identifier = $request->getPathInfo();

        if (substr(str_replace("/", "", $identifier), 0, strlen($route)) != $route) {
            return false;
        }

        $identifier = substr_replace($request->getPathInfo(), '', 0, strlen("/" . $route . "/"));
        $identifier = str_replace(Mage::helper('offinews')->getNewsitemUrlSuffix(), '', $identifier);

        $urlOption = Mage::helper('offinews')->getUrloption();

        if (substr($identifier, -1) == '/') {
            $identifier = substr_replace($identifier, '', -1);
        }
        if ($identifier == '') {
            $request->setModuleName('offinews')
                    ->setControllerName('index')
                    ->setActionName('index');
            return true;
        }
        if ($urlOption == 2) {
            $printEnable = 0;
            if (substr($identifier, 0, strlen('print/')) === 'print/') {
                $printEnable = 1;

                $identifier = substr_replace($identifier, '', 0, strlen('print/'));
            }
            $arrIndentifier = explode('/', $identifier);
            if (substr($identifier, 0, strlen('rss/')) === 'rss/') {
                $identifier = substr_replace($identifier, '', 0, strlen('rss/'));
                if ($identifier == '') {
                    $request->setModuleName('offinews')
                            ->setControllerName('rss')
                            ->setActionName('index');
                    return true;
                }
                $arrIndentifier = explode('/', $identifier);
                $identifierArticle = end($arrIndentifier);
                $getModel = Mage::helper('offinews')->getListIdentifier($arrIndentifier);
                $countElement = count($arrIndentifier) - count($getModel);
                if ($countElement == 0) {


                    if (Mage::helper('offinews')->checkURL($arrIndentifier, $getModel)) {
                        $request->setModuleName('offinews')
                                ->setControllerName('rss')
                                ->setActionName('index')
                                ->setParam('category', Mage::registry('current_offi_news_cat_identifier'));

                        if (strpos($request->getRequestUri(), '?page=')) {
                            $pageNumber = intval(strstr($identifier, '?page='));
                            $request->setParam('page', $pageNumber);
                        }
                        return true;
                    }
                }
                return false;
            } elseif (substr($identifier, 0, strlen('tag/')) === 'tag/') {
                $identifier = str_replace('tag/', '', $identifier);
                $param = trim(str_replace(Mage::helper('offinews')->getNewsitemUrlSuffix(), '', $identifier));
                $request->setModuleName('offinews')
                        ->setControllerName('index')
                        ->setActionName('tag')
                        ->setParam('tag', $param);
                return true;
            } else {
                $identifierArticle = end($arrIndentifier);
                $getModel = Mage::helper('offinews')->getListIdentifier($arrIndentifier);
                $countElement = count($arrIndentifier) - count($getModel);
                //if($printEnable)
                if ($countElement > 1) {
                    return false;
                } elseif ($countElement == 1) {
                    $urlPost = '/' . $arrIndentifier[count($arrIndentifier) - 1] . Mage::helper('offinews')->getNewsitemUrlSuffix();
                    if ($printEnable) {
                        $url = strstr($request->getPathInfo(), $urlPost, true) . Mage::helper('offinews')->getNewsitemUrlSuffix();
                        $url = str_replace('print/', '', $url);
                    } else {
                        $url = strstr($request->getPathInfo(), $urlPost, true) . Mage::helper('offinews')->getNewsitemUrlSuffix();
                    }
                    if (!$getModel) {
                        $request->setModuleName('offinews')
                                ->setControllerName('newspost')
                                ->setActionName('view')
                                ->setParam('key', $identifierArticle);
                        if ($printEnable) {
                            $request->setParam('mode', 'print');
                        }
                        return true;
                    } else {
                        unset($arrIndentifier[count($arrIndentifier) - 1]);
                        if (Mage::helper('offinews')->checkURL($arrIndentifier, $getModel)) {

                            $request->setModuleName('offinews')
                                    ->setControllerName('newspost')
                                    ->setActionName('view')
                                    ->setParam('key', $identifierArticle)
                                    ->setParam('category', Mage::registry('current_offi_news_cat_identifier'));
                            if ($printEnable) {
                                $request->setParam('mode', 'print');
                            }
                            return true;
                        }
                    }
                    return false;
                } else if ($countElement == 0) {
                    if (Mage::helper('offinews')->checkURL($arrIndentifier, $getModel)) {
                        $request->setModuleName('offinews')
                                ->setControllerName('index')
                                ->setActionName('index')
                                ->setParam('category', Mage::registry('current_offi_news_cat_identifier'));

                        if (strpos($request->getRequestUri(), '?page=')) {
                            $pageNumber = intval(str_replace('?page=', '', strstr($request->getRequestUri(), '?page=')));
                            $request->setParam('page', $pageNumber);
                        }
                        return true;
                    }
                    return false;
                }
                return false;
            }
        } else {

            if (substr($identifier, 0, 9) === 'category/') {
                $identifier = str_replace('category/', '', $identifier);
                $pageNumber = 1;
                if (strpos($request->getRequestUri(), '?page=')) {
                    $pageNumber = intval(str_replace('?page=', '', strstr($request->getRequestUri(), '?page=')));
                }
                $request->setModuleName('offinews')
                        ->setControllerName('index')
                        ->setActionName('index')
                        ->setParam('category', $identifier)
                        ->setParam('page', $pageNumber);
                return true;
            } elseif (substr($identifier, 0, 4) === 'rss/') {
                $identifier = str_replace('rss/', '', $identifier);
                if (substr($identifier, 0, 9) === 'category/') {
                    $identifier = str_replace('category/', '', $identifier);
                    $pageNumber = 1;
                    if (strpos($request->getRequestUri(), '?page=')) {
                        $pageNumber = intval(str_replace('?page=', '', strstr($request->getRequestUri(), '?page=')));
                    }

                    Mage::register('current_offi_news_cat_identifier', $identifier);
                    $request->setModuleName('offinews')
                            ->setControllerName('rss')
                            ->setActionName('index')
                            ->setParam('category', $identifier)
                            ->setParam('page', $pageNumber);
                    return true;
                } else {
                    return false;
                }
            } elseif (substr($identifier, 0, 4) === 'tag/') {
                $identifier = str_replace('tag/', '', $identifier);
                $param = trim(str_replace(Mage::helper('offinews')->getNewsitemUrlSuffix(), '', $identifier));
                $request->setModuleName('offinews')
                        ->setControllerName('index')
                        ->setActionName('tag')
                        ->setParam('tag', $param);
                return true;
            } else {
                if (strpos($request->getRequestUri(), '?mode=print')) {
                    $identifier = str_replace('?mode=print', '', $identifier);
                    $request->setModuleName('offinews')
                            ->setControllerName('newspost')
                            ->setActionName('print')
                            ->setParam('key', $identifier);
                    return true;
                } else {
                    $request->setModuleName('offinews')
                            ->setControllerName('newspost')
                            ->setActionName('view')
                            ->setParam('key', $identifier);
                    return true;
                }
            }
            return false;
        }
    }

}
