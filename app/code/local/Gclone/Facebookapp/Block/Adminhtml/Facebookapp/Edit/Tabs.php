<?php

class Gclone_Facebookapp_Block_Adminhtml_Facebookapp_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('facebookapp_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('facebookapp')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('facebookapp')->__('Item Information'),
          'title'     => Mage::helper('facebookapp')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('facebookapp/adminhtml_facebookapp_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}