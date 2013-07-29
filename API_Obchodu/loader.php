<?php
/**
 * Error reporting mode
 */
@error_reporting(E_ALL);
@restore_error_handler();
@restore_exception_handler();

/**
 * Set default timezone in PHP 5.
 */
if (function_exists( 'date_default_timezone_set'))
	@date_default_timezone_set('Europe/Prague');;

if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}  elseif (!ini_get('display_startup_errors')) {
    ini_set('display_startup_errors', '1');
}

/**
 * Header parameters UTF-8 encoding 
 */
iconv_set_encoding('internal_encoding', 'UTF-8');
extension_loaded('mbstring') && mb_internal_encoding('UTF-8');
@header('X-Powered-By: API-Jednicka v1'); 
//@header('Content-Type: text/plain'); 
@header('Cache-Control: no-cache');
ini_set('default_charset','utf-8');


require_once dirname(__FILE__) . '/src/Bootstrap.php';
require_once dirname(__FILE__) . '/src/Container.php';

require_once dirname(__FILE__) . '/src/Response/interfaces.php';
require_once dirname(__FILE__) . '/src/Response/Response.php';
require_once dirname(__FILE__) . '/src/Response/OrderCancel.php';
require_once dirname(__FILE__) . '/src/Response/OrderSend.php';
require_once dirname(__FILE__) . '/src/Response/OrderStatus.php';
require_once dirname(__FILE__) . '/src/Response/PaymentDelivery.php';
require_once dirname(__FILE__) . '/src/Response/PaymentStatus.php';
require_once dirname(__FILE__) . '/src/Response/ProductsAvailability.php';