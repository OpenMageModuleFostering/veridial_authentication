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
 * Account controller
 *
 * @category   Veridial
 * @package    Liquid11_Veridial
 * @author     Veridial <https://www.veridial.co.uk/>
 */

require_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'IndexController.php');
class Liquid11_Veridial_Adminhtml_IndexController extends Mage_Adminhtml_IndexController
{
    /**
     * Administrator login action
     * Delete cookies and start a fresh every attempt
     */
    public function loginAction()
    {
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            $this->_redirect('*');
            return;
        }
        $loginData = $this->getRequest()->getParam('login');
        $username = (is_array($loginData) && array_key_exists('username', $loginData)) ? $loginData['username'] : null;

        $this->loadLayout();
        $this->renderLayout();
        Mage::getModel('core/cookie')->delete('admin_id');
        Mage::getModel('core/cookie')->delete('admin_logout');
    }

    /**
     * Admin area entry point
     * If Veridial is enabled for Admins redirect to /veridial
     * Otherwise redirect to the startup page url
     */
    public function indexAction()
    {
        $veridial = Mage::getBlockSingleton('veridial/veridial');
        $session = Mage::getSingleton('admin/session');
        $url = $session->getUser()->getStartupPageUrl();

        if($veridial->getVeridialEnabled() == '1' && $veridial->getVeridialUseAdmin() == '1') {
            Mage::getModel('core/cookie')->set('logintype', 'admin');
            $url = 'veridial-authentication';
        } else {
            if ($session->isFirstPageAfterLogin()) {
                $session->setIsFirstPageAfterLogin(true);
            }
        }
        $this->_redirect($url);
        $adminID = $session->getUser()->getUserID();
        Mage::getModel('core/cookie')->set('admin_id', $adminID);
        Mage::getModel('core/cookie')->set('admin_logout', $this->getUrl('adminhtml/index/logout'));
    }
}
