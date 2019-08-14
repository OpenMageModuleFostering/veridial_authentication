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
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    /**
     * Check if the request is a PIN verification or not
     */
    if (isset($_POST['verify'])) {
        $pin = $_POST['Pin'];
        $token = $_POST['Token'];
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

        print($response);
    } else {
        /**
         * Fetch our Veridial API Key and Installation ID
         */
        $https_user = Mage::getStoreConfig('veridial/settings/installation');
        $https_password = Mage::getStoreConfig('veridial/settings/api');

        /**
         * Check what method is being called for
         * The action is POSTED via Ajax from /veridial frontend page
         */
        if($_POST['action'] == 'email'){
            $Email = $_POST['email'];
            $json = '{ EmailAddress:"'.$Email.'"}';
        } else {
            $Number = $_POST['number'];
            $json = '{ Number:"'.$Number.'"}';
        }

        /**
         * Set the correct Veridial API URL based on the action POSTED
         */
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'app':
                    $url = 'https://api.veridial.co.uk/App';
                    break;
                case 'sms':
                    $url = 'https://api.veridial.co.uk/Sms';
                    break;
                case 'voice':
                    $url = 'https://api.veridial.co.uk/Voice';
                    break;
                case 'email':
                    $url = 'https://api.veridial.co.uk/Email';
                    break;
            }

        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-length: '.strlen($json)
        ));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $https_user.':'.$https_password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);
        print($response);
    }
}