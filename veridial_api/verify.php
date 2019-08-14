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
$loginType = Mage::getModel('core/cookie')->get('logintype');

/**
 * Delete the 2FA cookie, so we can start a fresh below
 */
Mage::getModel('core/cookie')->delete('2FA');

$pin = $_POST['veridialPin'];
$token = $_POST['veridialToken'];
$json = '{ Pin:"'.$pin.'", Token:"'.$token.'"}';
$url = 'https://api.veridial.co.uk/Verify';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-length: '.strlen($json)
));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
curl_close($ch);

$reply = json_decode($response, true);

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if($reply["StatusCode"] == 1006){
        Mage::getModel('core/cookie')->set('2FA', 'passed', '0', '/');
        if($loginType == 'customer'){
            $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'customer/account';
            Mage::app()->getResponse()->setRedirect($url)->sendResponse();
        } else {
            $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'admin';
            Mage::app()->getResponse()->setRedirect($url)->sendResponse();
        }
    } else {
        Mage::getModel('core/cookie')->set('2FA','failed','0','/');
        echo
            "<h1>Sorry, there was a problem</h1>".
            $reply["StatusDescription"]."<br />".
            "<a href='".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'veridial'."Please try again</a>";
    }
}
?>