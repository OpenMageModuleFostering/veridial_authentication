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
require_once(Mage::getModuleDir('controllers','Mage_Customer').DS.'AccountController.php');
class Liquid11_Veridial_AccountController extends Mage_Customer_AccountController
{
    /**
     * Check if the customer is logged in or not
     */
    function isCustomerLoggedIn(){
        if(!Mage::getSingleton('customer/session')->isLoggedIn()){
            return false;
        } else{
            return true;
        }
    }
    /**
     * Ensure customer is logged in
     * Then set the URL to go to
     */
    protected function _loginPostRedirect()
    {
        $session = $this->_getSession();
        $veridial = Mage::getBlockSingleton('veridial/veridial');

        if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl()) {
            // Set default URL to redirect customer to
            $session->setBeforeAuthUrl($this->_getHelper('customer')->getAccountUrl());
            // Redirect customer to the last page visited after logging in
            if ($session->isLoggedIn()) {
                if (!Mage::getStoreConfigFlag(
                    Mage_Customer_Helper_Data::XML_PATH_CUSTOMER_STARTUP_REDIRECT_TO_DASHBOARD
                )) {
                    $referer = $this->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME);
                    if ($referer) {
                        // Rebuild referer URL to handle the case when SID was changed
                        $referer = $this->_getModel('core/url')
                            ->getRebuiltUrl( $this->_getHelper('core')->urlDecodeAndEscape($referer));
                        if ($this->_isUrlInternal($referer)) {
                            $session->setBeforeAuthUrl($referer);
                        }
                    }
                } else if ($session->getAfterAuthUrl()) {
                    $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
                }
            } else {
                $session->setBeforeAuthUrl( $this->_getHelper('customer')->getLoginUrl());
            }
        } else if ($session->getBeforeAuthUrl() ==  $this->_getHelper('customer')->getLogoutUrl()) {
            $session->setBeforeAuthUrl( $this->_getHelper('customer')->getDashboardUrl());
        } else {
            if (!$session->getAfterAuthUrl()) {
                $session->setAfterAuthUrl($session->getBeforeAuthUrl());
            }
            if ($session->isLoggedIn()) {
                $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
            }
        }

        $isCustomerLoggedIn = $this->isCustomerLoggedIn();
        if(!$isCustomerLoggedIn){
            if($veridial->getVeridialEnabled() == '1' && $veridial->getVeridialUseCustomer() == '1'){
                Mage::getModel('core/cookie')->set('logintype', 'customer');
                $url = Mage::getUrl('customer/account/login');
                $this->_redirectUrl($url);
            } else {
                $this->_redirectUrl($session->getBeforeAuthUrl(true));
            }
        } else{
            if($veridial->getVeridialEnabled() == '1' && $veridial->getVeridialUseCustomer() == '1'){
                Mage::getModel('core/cookie')->set('logintype', 'customer');
                $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'veridial-authentication';
                $this->_redirectUrl($url);
            } else {
                $this->_redirectUrl($session->getBeforeAuthUrl(true));
            }
        }
    }
}