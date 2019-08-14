<?php

class Officience_News_Model_Config_Source_Dateformat {

    public function toOptionArray() {
        return array(
            array('value' => 1, 'label' => (date('d/M/Y') . '<div>&nbsp</div>')),
            array('value' => 2, 'label' => (date('Y/m/d') . '<div>&nbsp</div>')),
            array('value' => 3, 'label' => (date('m/d/Y') . '<div>&nbsp</div>')),
            array('value' => 4, 'label' => (date('d/m/Y') . '<div>&nbsp</div>')),
        );
    }

}

?>
