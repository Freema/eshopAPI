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
    
    /** @var integer */
    private $_country_id = 56;
    
    /** @var integer */
    private $_sysvar = 1;
    
    /** @var string */
    private $_webapi_key;
    
    /** @var bool */
    private $_debug = FALSE;
    
    public function __construct($login, $pass, $apiKey, $wsld = 'http://webapi.aukro.cz/uploader.php?wsdl') {
        
        $this->_webapi_key = $apiKey;
        
        $client = new SoapClient($wsld);
        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = false;
        
        $this->_client = $client;
    }
    
    /**
     * @param integer $id
     * @return Api
     */
    public function setCountryId($id)
    {
        $this->_country_id = (int) $id;
        
        return $this;
    }
    
    public function getApiVerKey()
    {
        $params = array(
            'sysvar' => $this->_sysvar,
            'country-id' => 56,
            'webapi-key' => $this->_webapi_key,
            );
        $output = $this->_client->__soapCall('doQuerySysStatus', $params); 
        
        return $output;
    }
    
}