<?php

class Officience_News_Block_Adminhtml_Category_Grid_Column_Renderer_SubCategories extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $strspace = '&nbsp&nbsp&nbsp&nbsp';
        $value = $row->getData($this->getColumn()->getIndex());
        if ($row->getData('level') == 1) {
            return '<span>' . str_repeat($strspace, 1) . $value . '</span>';
        } else if ($row->getData('level') == 2) {
            return '<span>' . str_repeat($strspace, 2) . $value . '</span>';
        } else if ($row->getData('level') == 3) {
            return '<span>' . str_repeat($strspace, 3) . $value . '</span>';
        } else if ($row->getData('level') == 4) {
            return '<span>' . str_repeat($strspace, 4) . $value . '</span>';
        } else if ($row->getData('level') == 5) {
            return '<span>' . str_repeat($strspace, 5) . $value . '</span>';
        } else {
            return $value;
        }
    }

}

?>
