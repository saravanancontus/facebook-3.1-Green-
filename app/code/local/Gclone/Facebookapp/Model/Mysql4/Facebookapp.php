<?php

class Gclone_Facebookapp_Model_Mysql4_Facebookapp extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the facebookapp_id refers to the key field in your database table.
        $this->_init('facebookapp/facebookapp', 'facebookapp_id');
    }
}