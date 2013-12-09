<?php
class Gclone_Facebookapp_Block_Facebookapp extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getFacebookapp()     
     { 
        if (!$this->hasData('facebookapp')) {
            $this->setData('facebookapp', Mage::registry('facebookapp'));
        }
        return $this->getData('facebookapp');
        
    }
}