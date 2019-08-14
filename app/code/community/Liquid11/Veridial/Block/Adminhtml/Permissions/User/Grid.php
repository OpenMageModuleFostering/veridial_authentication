<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Edit this file at your own risk, any adjustments made may prevent
 * the extension from working correctly and/or cause security issues.
 *
 * @category   Veridial
 * @package    Liquid11_Veridial
 * @copyright  Copyright (c) Veridial <https://www.veridial.co.uk/>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml permissions user grid
 *
 * @category   Veridial
 * @package    Liquid11_Veridial
 * @author     Veridial <https://www.veridial.co.uk/>
 */
class Liquid11_Veridial_Block_Adminhtml_Permissions_User_Grid extends Mage_Adminhtml_Block_Permissions_User_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('permissionsUserGrid');
        $this->setDefaultSort('username');
        $this->setDefaultDir('asc');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('admin/user_collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('user_id', array(
            'header'    => Mage::helper('adminhtml')->__('ID'),
            'width'     => 5,
            'align'     => 'right',
            'sortable'  => true,
            'index'     => 'user_id'
        ));

        $this->addColumn('username', array(
            'header'    => Mage::helper('adminhtml')->__('User Name'),
            'index'     => 'username'
        ));

        $this->addColumn('firstname', array(
            'header'    => Mage::helper('adminhtml')->__('First Name'),
            'index'     => 'firstname'
        ));

        $this->addColumn('lastname', array(
            'header'    => Mage::helper('adminhtml')->__('Last Name'),
            'index'     => 'lastname'
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('adminhtml')->__('Email'),
            'width'     => 40,
            'align'     => 'left',
            'index'     => 'email'
        ));

        /**
         * Add Admin user's mobile number to Grid
         */
        $this->addColumn('veridial_mobile', array(
            'header'    => Mage::helper('adminhtml')->__('Mobile Number'),
            'width'     => 40,
            'align'     => 'left',
            'index'     => 'veridial_mobile'
        ));

        /**
         * Add Admin user's landling number to Grid
         */
        $this->addColumn('veridial_landline', array(
            'header'    => Mage::helper('adminhtml')->__('Telephone Number'),
            'width'     => 40,
            'align'     => 'left',
            'index'     => 'veridial_landline'
        ));

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('adminhtml')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array('1' => Mage::helper('adminhtml')->__('Active'), '0' => Mage::helper('adminhtml')->__('Inactive')),
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('user_id' => $row->getId()));
    }

    public function getGridUrl()
    {
        //$uid = $this->getRequest()->getParam('user_id');
        return $this->getUrl('*/*/roleGrid', array());
    }

}
