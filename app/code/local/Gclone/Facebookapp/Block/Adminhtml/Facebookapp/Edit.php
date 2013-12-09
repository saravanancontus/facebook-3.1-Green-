<?php

class Gclone_Facebookapp_Block_Adminhtml_Facebookapp_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'facebookapp';
        $this->_controller = 'adminhtml_facebookapp';
        
        $this->_updateButton('save', 'label', Mage::helper('facebookapp')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('facebookapp')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('facebookapp_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'facebookapp_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'facebookapp_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('facebookapp_data') && Mage::registry('facebookapp_data')->getId() ) {
            return Mage::helper('facebookapp')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('facebookapp_data')->getTitle()));
        } else {
            return Mage::helper('facebookapp')->__('Add Item');
        }
    }
}