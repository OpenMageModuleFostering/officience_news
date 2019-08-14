<?php


class Officience_News_Model_Check extends Mage_Core_Model_Abstract
{

    public function checkExtensions()
    {
        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        sort($modules);

        $magentoPlatform = Officience_News_Helper_Versions::getPlatform();
        foreach ($modules as $extensionName) {
            if (strstr($extensionName, 'Officience_') === false) {
                continue;
            }
            if ($extensionName == 'Officience_Core' || $extensionName == 'Officience_All') {
                continue;
            }
            if ($platformNode = $this->getExtensionPlatform($extensionName)) {
                $extensionPlatform = Officience_News_Helper_Versions::convertPlatform($platformNode);
                if ($extensionPlatform < $magentoPlatform) {
                    $this->disableExtensionOutput($extensionName);
                    Mage::getSingleton('adminhtml/session')
                    ->addError(Mage::helper('offinews')->__('Platform version is not correct for News module!'));
                    return;
                }
            }
        }
        return $this;
    }

    public function getExtensionPlatform($extensionName)
    {
        try {
            if ($platform = Mage::getConfig()->getNode("modules/$extensionName/platform")) {
                $platform = strtolower($platform);
                return $platform;
            } else {
                throw new Exception();
            }
        } catch (Exception $e) {
            return false;
        }
    }


    public function disableExtensionOutput($extensionName)
    {
        $coll = Mage::getModel('core/config_data')->getCollection();
        $coll->getSelect()->where("path='advanced/modules_disable_output/$extensionName'");
        $i = 0;
        foreach ($coll as $cd) {
            $i++;
            $cd->setValue(1)->save();
        }
        if ($i == 0) {
            Mage::getModel('core/config_data')
                    ->setPath("advanced/modules_disable_output/$extensionName")
                    ->setValue(1)
                    ->save();
        }
        return $this;
    }

    public function checkConfiguration()
    {
        $coll = Mage::getModel('core/config_data')->getCollection();
        $coll->getSelect()->where("path='offinews/news/showrightblock'");
        foreach ($coll as $cd) {
            if ($cd->getValue() == 1) {
                $loll = Mage::getModel('core/config_data')->getCollection();
                $loll->getSelect()->where("path='offinews/news/showleftblock'");
                foreach ($loll as $ld) {
                    if ($ld->getValue() == 1) {
                        $ld->setValue(0)->save();
                        Mage::getSingleton('adminhtml/session')
                        ->addSuccess(Mage::helper('offinews')->__('News category tree can be shown only in one column'));
                    }
                }
            }
        }
        return $this;
    }


}
