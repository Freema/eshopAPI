<?php
namespace AukroAPI;

use Exception;
use SoapClient;

/**
 * include container!
 */
require_once dirname(__FILE__) . '/src/AukroApiConnection.php';


/**
 * Check PHP configuration.
 */
if (version_compare(PHP_VERSION, '5.2.0', '<')) {
    throw new Exception('Aukro API needs PHP 5.2.0 or newer.');
}

if (!class_exists('SoapClient')) {
    throw new Exception('AukroAPI need SoapClient class');
}   

class Api {
    
    /** @var SoapClient */
    private $_client;
    
    public function __construct($wsld = NULL) {
        if(empty($wsld))
        {
            $wsld = 'http://webapi.aukro.cz/uploader.php?wsdl';
        }
        
        $this->_client = new SoapClient($wsdl);
    }
    
}