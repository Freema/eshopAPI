<?php
namespace AukroAPI;

use Exception;

/**
 * include container!
 */
require_once dirname(__FILE__) . '/src/interface.php';
require_once dirname(__FILE__) . '/src/AukroApiContainer.php';
require_once dirname(__FILE__) . '/src/AukroApiEvent.php';
require_once dirname(__FILE__) . '/src/AukroApiResult.php';
require_once dirname(__FILE__) . '/src/FormHelpers/BaseFormHelper.php';
require_once dirname(__FILE__) . '/src/FormHelpers/NetteFormHelper.php';

/**
 * Check PHP configuration.
 */
if (version_compare(PHP_VERSION, '5.2.0', '<')) {
    throw new Exception('Aukro API needs PHP 5.2.0 or newer.');
}

if (!extension_loaded('openssl')) {
    throw new Exception('AukroAPI need openssl extension.');
}
  
if (!extension_loaded('soap')) {
    throw new Exception('The SOAP extension is not available.');
}  
/**
 * Description of AukroApiContainer
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class Api {
   
    const HTML_FORM_HELPER = 'html_form';
    const NETTE_FORM_HELPER = 'nette_form';
    const SYPHONY_FORM_HELPER = 'syphony_form';

    /** @var integer */
    private $_country_id;
    
    /** @var integer */
    private $_sysvar = 1;
    
    /** @var string */
    private $_login;
    
    /** @var integer */
    private $_pass;

    /** @var string */
    private $_webapi_key;
    
    /** @var array */
    private $_api_version;
    
    /** @var string */
    private $_wsld;
    
    /** @var AukroApiContainer */
    private $_container;
    
    /**
     * @param string $login Přihlašovací jméno do aukro shopu
     * @param string $pass Přihlašovací heslo do aukro shopu
     * @param string $apiKey Klič pro aukro api který dostanete na aukro podpoře
     * @param string $wsld Defaultně je nastaveno 56(Aukro.cz) pro testování muže být použito 228(http://www.testwebapi.pl/) 
     */
    function __construct($login, $pass, $apiKey, $country_id = 56, $wsld = 'http://webapi.aukro.cz/uploader.php?wsdl') {
       
        $this->_login = $login;
        $this->_pass = $pass;
        $this->_webapi_key = $apiKey;
        $this->_country_id = $country_id;
        $this->_wsld = $wsld;
        $this->soap_defencoding = 'UTF-8';
        $this->decode_utf8 = false;

        $this->_api_version = $this->_setApiVerKey();
    }
    
    /**
     * @return \AukroAPI\NetteFormHelper
     */
    public function getNetteFormHelper()
    {
        return new NetteFormHelper($this);
    }
    
    /**
     * @return \AukroAPI\NetteFormHelper
     */
    public function getBaseFormHelper()
    {
        return new BaseFormHelper($this); 
    }
    
    /**
     * @return AukroApiContainer
     */
    public function getApiContainer()
    {
        if($this->_container instanceof AukroApiContainer || empty($this->_container))
        {
            $this->_container = new AukroApiContainer($this->_wsld);
            return $this->_container;
        }
        else
        {
            return $this->_container;
        }
    }
            
    /**
     * @return integer
     */
    public function getCountryId()
    {
        return $this->_country_id;
    }
    
    /**
     * @return integer
     */
    public function getSysvar()
    {
        return $this->_sysvar;
    }
    
    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->_login;
    }
    
    /**
     * @return string
     */
    public function getPass()
    {
        return $this->_pass;
    }
    
    /**
     * @return string
     */
    public function getWebApiKey()
    {
        return $this->_webapi_key;      
    }
    
    public function getFormHelper()
    {
        return $this->form_helper;
    }

    /**
     * Přihlášení k WebAPI
     * 
     * @return IEvents
     */
    public function login()
    {
        $pass_hash = self::AkroHashPassword($this->_pass);

        $params = array(
            'user-login' => $this->_login,
            'user-hash-password' => $pass_hash,
            'country-code' => $this->_country_id,
            'webapi-key' => $this->_webapi_key,
            'local-version' => $this->_api_version['ver-key']);
        $output = $this->getApiContainer()->__soapCall('doLoginEnc', $params);
        $event = new Event($this->getApiContainer(), $output, $this->_api_version['ver-key']);
        return $event;
    }
    
    /**
     * Set Api key value
     * 
     * @return array
     */
    private function _setApiVerKey()
    {
        $params = array(
            'sysvar' => $this->_sysvar,
            'country-id' => $this->_country_id,
            'webapi-key' => $this->_webapi_key,
            );
        $output = $this->getApiContainer()->__soapCall('doQuerySysStatus', $params); 
                
        return $output;
    }
    
    /**
     * Získaní verze WebAPI
     * 
     * @return \AukroAPI\AukroApiResult
     */
    public function apiVerInfo()
    {
        $output = $this->_api_version;        
        return new AukroApiResult($output);
    }
    
    /**
     * Vypíše seznam všech kategorií na Aukru.
     * @return AukroApiResult
     */
    final function cartData()
    {
        $params = array(
          'country-code'    => $this->getCountryId(),
          'local-version'   => $this->_api_version['ver-key'],
          'webapi-key'      => $this->getWebApiKey(),  
        );
        
        $output = $this->getApiContainer()->__soapCall('doGetCatsData', $params);
        
        return new AukroApiResult($output);        
    }    
    
    /**
     * Vypíše obsah prodejního formuláře pro kategorii 
     * 
     * @param integer $cat_id Id kategorie
     * @return \AukroAPI\AukroApiResult
     */
    public function sellFormFields($cat_id)
    {
        $params = array(
            'webapi-key' => $this->_webapi_key,
            'country-id' => $this->_country_id,
            'cat-id' => $cat_id
        );
        $output = $this->getApiContainer()->__soapCall('doGetSellFormFieldsForCategory', $params);        
        
        return new AukroApiResult($output);
    }
    
    /**
     * Password Hash
     * 
     * @param integer $pass
     * @return string
     */
    public static function AkroHashPassword($pass)
    {
        if(function_exists('hash')){
          $password = base64_encode(hash('sha256', $pass, true));
        }
        else {
          $password = base64_encode(mhash(MHASH_SHA256, $pass));
        }
        
        return $password;
    }
}


class AukroApiException extends Exception {}
