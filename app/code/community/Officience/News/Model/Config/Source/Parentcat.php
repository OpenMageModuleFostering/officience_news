<?php

class Officience_News_Model_Config_Source_Parentcat {

    public function toOptionArray() {
        return array(
            array('value' => 1, 'label' => Mage::helper('offinews')->__('Show list products')),
            array('value' => 2, 'label' => Mage::helper('offinews')->__('Show list categories')),
        );
    }

}

?>
