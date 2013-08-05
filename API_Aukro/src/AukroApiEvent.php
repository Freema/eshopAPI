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

    /**
     * Informace o přihlašeným uživateli.
     * @return AukroApiResult
     */
    final function loginInformation()
    {
        return new AukroApiResult($this->_loginInformation);
    }
    
    /**
    * Ověření zda se na učtě něco prodalo.
    * @return AukroApiResult 
    */
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
    
    /**
     * Získa id transakce
     * @param array $auctions_arr
     * @return \AukroAPI\AukroApiResult
     */
    final function TransactionsId(array $auctions_arr)
    {
        $data = $this->loginInformation();
        $params = array(
            'session-handle' => $data->session_handle_part,
            'items-id-array' => $auctions_arr,
            'user-role' => 'seller',            
        );
        
        $output = $this->_client->__soapCall('doGetTransactionsIDs', $params);
        return new AukroApiResult($output);
    }
    
    /**
    * Získání seznamu uložených aukcí. 
    * @retrun AukroApiResult
    */    
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