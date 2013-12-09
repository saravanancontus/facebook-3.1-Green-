<?php
class Gclone_Facebookapp_Block_Adminhtml_Facebookapp extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_facebookapp';
    $this->_blockGroup = 'facebookapp';
    $this->_headerText = Mage::helper('facebookapp')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('facebookapp')->__('Add Item');
    parent::__construct();
  }
}