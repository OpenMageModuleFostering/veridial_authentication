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
 * Check Adminhtml session to ensure user is Authorized
 *
 * @category   Veridial
 * @package    Liquid11_Veridial
 * @author     Veridial <https://www.veridial.co.uk/>
 */
class Liquid11_Veridial_Model_Adminhtml_Session extends Mage_Adminhtml_Model_Session
{
    public function __construct()
    {
        $this->init('adminhtml');

        $VeridialAuth = Mage::getModel('core/cookie')->get('2FA');
        if($VeridialAuth == "pending"){
            $veridial = Mage::getBlockSingleton('veridial/veridial');
            if($VeridialAuth == "pending" && $veridial->getVeridialEnabled() == '1' && $veridial->getVeridialUseAdmin() == '1'){
                $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'veridial-authentication';
                $response = Mage::app()->getFrontController()->getResponse();
                $response->setRedirect($url);
            }
        }
    }
}
