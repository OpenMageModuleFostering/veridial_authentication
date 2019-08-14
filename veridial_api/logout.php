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
 * Veridial initial API post
 * This post will talk to Veridial and communicate details of your API key and Installation ID
 * If all is OK Veridial will send back a Token which is stored
 * @category   Veridial
 * @package    Liquid11_Veridial
 * @author     Veridial <https://www.veridial.co.uk/>
 */

/**
 * Call Mage (Because we're outside of Magento now
 * Setup the Mage Class to enable us to retrieve important details
 */
define('MAGENTO_ROOT', dirname(dirname(__FILE__)));
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require_once $mageFilename;
umask(0);
Mage::app();

/**
 * Programmatically force admin logout and set the Veridial "2FA" Cookie as FAILED
 */
Mage::getModel('core/cookie')->set('2FA', 'failed', '0', '/');
$url = Mage::getModel('core/cookie')->get('admin_logout');
Mage::app()->getResponse()->setRedirect($url)->sendResponse();
?>