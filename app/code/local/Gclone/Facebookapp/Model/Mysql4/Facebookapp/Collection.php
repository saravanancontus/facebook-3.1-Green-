<?php

class Gclone_Facebookapp_Model_Mysql4_Facebookapp_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('facebookapp/facebookapp');
    }
}