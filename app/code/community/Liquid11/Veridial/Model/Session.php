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
 * Check Customer session to ensure user is Authorized
 *
 * @category   Veridial
 * @package    Liquid11_Veridial
 * @author     Veridial <https://www.veridial.co.uk/>
 */
require_once(Mage::getModuleDir('','Mage_Customer').DS.'Model/Session.php');
class Liquid11_Veridial_Model_Session extends Mage_Customer_Model_Session
{
    public function __construct()
    {
        $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $veridial = Mage::getBlockSingleton('veridial/veridial');
        if (strpos($url,'veridial-authentication') == false && strpos($url,'veridial') == false && strpos($url,'veridial_api') == false) {
            $VeridialAuth = Mage::getModel('core/cookie')->get('2FA');
            if ($VeridialAuth == "pending") {
                if ($VeridialAuth != "passed" && $veridial->getVeridialEnabled() == '1' && $veridial->getVeridialUseCustomer() == '1') {
                    $url = Mage::helper('customer')->getLogoutUrl();
                    Mage::app()->getResponse()->setRedirect($url);
                    Mage::getModel('core/cookie')->set('2FA', 'failed', '0', '/');
                }
            }
        }
        $namespace = 'customer';
        if ($this->getCustomerConfigShare()->isWebsiteScope()) {
            $namespace .= '_' . (Mage::app()->getStore()->getWebsite()->getCode());
        }

        $this->init($namespace);
        Mage::dispatchEvent('customer_session_init', array('customer_session' => $this));
    }
}