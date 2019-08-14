<?php

class Officience_News_Helper_Data extends Mage_Core_Helper_Abstract {

    const UNAPPROVED_STATUS = 0;
    const APPROVED_STATUS = 1;
    const XML_PATH_ENABLED = 'offinews/news/enabled';
    const XML_PATH_TITLE = 'offinews/news/title';
    const XML_PATH_MENU_LEFT = 'offinews/news/menuLeft';
    const XML_PATH_MENU_RIGHT = 'offinews/news/menuRoght';
    const XML_PATH_FOOTER_ENABLED = 'offinews/news/footerEnabled';
    const XML_PATH_LAYOUT = 'offinews/news/layout';

    public function isEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_ENABLED);
    }

    public function isTitle() {
        return Mage::getStoreConfig(self::XML_PATH_TITLE);
    }

    public function isMenuLeft() {
        return Mage::getStoreConfig(self::XML_PATH_MENU_LEFT);
    }

    public function isMenuRight() {
        return Mage::getStoreConfig(self::XML_PATH_MENU_RIGHT);
    }

    public function isFooterEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_FOOTER_ENABLED);
    }

    public function isLayout() {
        return Mage::getStoreConfig(self::XML_PATH_LAYOUT);
    }

    public function getUserName() {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim("{$customer->getFirstname()} {$customer->getLastname()}");
    }

    public function getUrloption() {
        return Mage::getStoreConfig('offinews/urloption/urlnews');
    }

    public function getRoute() {
        $route = Mage::getStoreConfig('offinews/news/route');
        if (!$route) {
            $route = "news";
        }
        return $route;
    }

    public function getUserEmail() {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }

    public function getRssLink($categoryId) {
        if ($categoryId) {
            return Mage::getUrl('offinews/rss', array('category' => $categoryId));
        } else {
            return Mage::getUrl('offinews/rss');
        }
    }

    public function getFileUrl($newsitem) {
        $file = Mage::getBaseDir('media') . 'offinews' . DS . $newsitem->getDocument();
        $file = str_replace(Mage::getBaseDir('media'), Mage::getBaseUrl('media'), $file);
        $file = str_replace('\\', '/', $file);
        return $file;
    }

    public function showAuthor() {
        return Mage::getStoreConfig('offinews/news/showauthorofnews');
    }

    public function showCategory() {
        return Mage::getStoreConfig('offinews/news/showcategoryofnews');
    }

    public function showDate() {
        return Mage::getStoreConfig('offinews/news/showdateofnews');
    }

    public function showTime() {
        return Mage::getStoreConfig('offinews/news/showtimeofnews');
    }

    public function enableLinkRoute() {
        return Mage::getStoreConfig('offinews/news/enablelinkrout');
    }

    public function getLinkRoute() {
        return Mage::getStoreConfig('offinews/news/linkrout');
    }

    public function getPrintEnable() {
        return Mage::getStoreConfig('offinews/news/printable');
    }

    public function getRequireLogin() {
        return Mage::getStoreConfig('offinews/comments/need_login');
    }

    public function getCaptchaEnable() {
        return Mage::getStoreConfig('offinews/captcha/enabled');
    }

    public function getTagsAccess() {
        return Mage::getStoreConfig('offinews/news/tags');
    }

    public function getNewsitemUrlSuffix() {
        return Mage::getStoreConfig('offinews/news/itemurlsuffix');
    }
    
    public function getparentcategorydisplaysetting()
    {
        return Mage::getStoreConfig('offinews/news/parentcategory');
    }

    public function formatDate($date) {

        $date = Mage::helper('core')->formatDate($date, 'short', true);
        if (!Mage::helper('offinews')->showTime()) {
            $date = Mage::helper('core')->formatDate($date, 'short', true);
        } else {
            $date = Mage::helper('core')->formatDate($date, 'short', false);
        }
        return $date;
    }

    public function getRssEnable() {
        return Mage::getStoreConfig('offinews/rss/enable');
    }

    public function vnFilter($str) {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($str));
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }

    public function addAfterElement($id, $value = 1) {
        $html = "";
        $html.= '<td class="scope-label"><span class="nobr">[STORE VIEW]</span></td>';
        $html.='<td class="value use-default">';
        $html.= '<input type="checkbox"';
        $html .= $value ? ' checked="checked" ' : '';
        $html.=' value="1" id="' . $id . '_default"  onclick="toggleValueElements(this, this.parentNode.parentNode)"  name="default_value[' . $id . ']" />';
        $html.='<label class="normal" for="' . $id . '_default">Use Default Value</label>';
        $html.= '<td>';
        return $html;
    }

    public function getDefaultValueData($data) {
        $arr = array();

        if (is_array($data))
            $defaulValueList = explode(',', $data['default_value']);
        elseif ($data)
            $defaulValueList = explode(',', $data);
        foreach ($defaulValueList as $value) {
            $temp = explode('->', $value);
            if (count($temp) == 2) {
                $arr[$temp[0]] = $temp[1];
            }
        }
        return $arr;
    }

    public function renderDefaultToText($data) {
        foreach ($data['default_value'] as $key => $default_value) {
            $arrListDefaultvalue[$key] = $default_value;
        }
        $arrTemp = array();
        foreach ($arrListDefaultvalue as $key => $value) {
            $arrTemp[] = $key . '->' . $value;
        }
        return implode(',', $arrTemp);
    }

    public function listDefaultCat() {
        $str = 'title->1,identifier->1,category_status->1,display_setting->1,sort_order->1,description->1,meta_keywords->1,meta_description->1,default_value->1';
        return $str;
    }

    public function listDefaultNews() {
        $str = 'title->1,identifier->1,description->1,full_content->1,news_status->1,publicate_from_date->1,publicate_to_date->1,publicate_from_time->1,publicate_to_time->1,author->1,meta_keywords->1,meta_description->1,comments_enabled->1,tags->1,sort_order->1,default_value->1';
        return $str;
    }

    public function getListIdentifier($listIndetifier) {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $table = Mage::getSingleton('core/resource')->getTableName('officience_category_store');
        $select = $connection->select()
                ->from(array($table), array('category_id', 'identifier', 'path'))
                ->where('identifier in (?)', $listIndetifier);
        $stmt = $connection->query($select);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function checkURL($arrIndentifier, $model) {
        $endIdentifier = end($arrIndentifier);

        $path = "";
        $curCatId = "";
        $curCatIdentifier = "";
        foreach ($model as $valueTemp) {
            if ($valueTemp['identifier'] == $endIdentifier) {
                $path = $valueTemp['path'];
                $curCatId = $valueTemp['category_id'];
                $curCatIdentifier = $valueTemp['identifier'];
            }
        }
        $arrPath = explode('/', $path);
        $arr = array();
        foreach ($arrPath as $value) {
            foreach ($model as $valueCate) {
                if ($valueCate['category_id'] == $value) {
                    $arr[] = $valueCate['identifier'];
                }
            }
        }
        if (count(array_diff($arr, $arrIndentifier)) < 1 && count(array_diff($arrIndentifier, $arr)) < 1) {
//            Mage::register('current_offi_news_cat_url', $curUrl);
            if ($path)
                Mage::register('current_offi_news_cat_path', $path);
            if ($curCatId)
                Mage::register('current_offi_news_cat_id', $curCatId);
            if ($curCatIdentifier)
                Mage::register('current_offi_news_cat_identifier', $curCatIdentifier);
            return true;
        }
        return false;
    }

}
