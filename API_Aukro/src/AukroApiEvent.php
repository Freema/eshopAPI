<?php
namespace AukroAPI;

/**
 * Description of AukroApiEvent
 * Events that needs a login to the application
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class Event implements IEvents {
    
    /** API options */
    const ACCONT_TYPE = 'watch_cl';
    
    /** @var \HeurekaAPI\Api */
    private $_client;
   
    /** @var integer */
    private $_api_version;
    
    /** @var array */
    private $_loginInformation;
    
    /**
     * @param array $loginInformation
     * @param integer $apiVersionKey
     */
    function __construct(Api $client, $loginInformation, $apiVersionKey) {
        
        $this->_client = $client;
        $this->_loginInformation = $loginInformation;
        $this->_api_version = $apiVersionKey;
    }

    final function loginInformation()
    {
        return new AukroApiResult($this->_loginInformation);
    }
    
    final function logDeals()
    {
        $data = $this->loginInformation();
        $params = array(
            'session-id' => $data->session_handle_part,
            'journal-start' => 0
            );
        $output = $this->_client->__soapCall('doGetSiteJournalDeals', $params);
        return new AukroApiResult($output);
    }
    
    final function listOfSavedAuctions()
    {
        $data = $this->loginInformation();
        $params = array(
            'session-handle' => $data->session_handle_part,
            'account-type' => self::ACCONT_TYPE,
            'offset' => 0,
            'items-array' => '');

        $output = $this->_client->__soapCall('doMyAccount2', $params);        
        return new AukroApiResult($output);
    }
}