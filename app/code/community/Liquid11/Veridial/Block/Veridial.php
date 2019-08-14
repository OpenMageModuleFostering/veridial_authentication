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
 * Veridial configuration block
 *
 * @category   Veridial
 * @package    Liquid11_Veridial
 * @author     Veridial <https://www.veridial.co.uk/>
 */
class Liquid11_Veridial_Block_Veridial extends Mage_Adminhtml_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getVeridial()
     { 
        if (!$this->hasData('veridial')) {
            $this->setData('veridial', Mage::registry('veridial'));
        }
        return $this->getData('veridial');
    }

    /**
     * @return bool
     */
    public function getVeridialEnabled(){
        return Mage::getStoreConfigFlag('veridial/settings/is_enabled');
    }

    /**
     * @return bool
     */
    public function getVeridialUseAdmin(){
        return Mage::getStoreConfigFlag('veridial/settings/use_admin');
    }

    /**
     * @return bool
     */
    public function getVeridialUseCustomer(){
        return Mage::getStoreConfigFlag('veridial/settings/use_customer');
    }

    /**
     * @return string
     */
    public function getVeridialInstallationId(){
        return Mage::getStoreConfig('veridial/settings/installation');
    }

    /**
     * @return string
     */
    public function getVeridialApiKey(){
        return Mage::getStoreConfig('veridial/settings/api');
    }

    /**
     * @return string
     */
    public function getVeridialBillingDay(){
        return Mage::getStoreConfig('veridial/settings/billing');
    }

    /**
     * @return array
     */
    public function getVeridialCustomerMethod(){
        return Mage::getStoreConfig('veridial/settings/customer_method');
    }

    /**
     * @return array
     */
    public function getVeridialAdminMethod(){
        return Mage::getStoreConfig('veridial/settings/admin_method');
    }

    /**
     * @return string
     */
    public function getVeridialAjaxUrl(){
        return Mage::getUrl('veridial/index/ajaxpost/');
    }

    /**
     * @return string
     */
    public function getVeridialBaseUrl(){
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
    }

    /**
     * Check if logintype cookie is set & if it's value == 'admin'
     */
    public function getVeridialAdmin(){
        if ($_COOKIE['logintype'] == 'admin' ) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Check if logintype cookie is set & if it's value == 'customer'
     */
    public function getVeridialCustomer(){
        if ($_COOKIE['logintype'] == 'customer' ) {
            return True;
        } else {
            return False;
        }
    }

}