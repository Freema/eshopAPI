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
    final function transactionsId(array $auctions_arr)
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
     * Informace vyplněné kupujícími ve formuláři
     * @param type $trans_ids
     * @return \AukroAPI\AukroApiResult
     */
    final function transactionsInformation($trans_ids)
    {
        $data = $this->loginInformation();
        $params = array(
            'session-handle' => $data->session_handle_part,
            'transactions-ids-array' => $trans_ids
                );
        $output = $this->_client->__soapCall('doGetPostBuyFormsDataForSellers', $params);
        return new AukroApiResult($output);
    }
    
    /**
     * Informace o kupujicím
     * @param array $auctions_arr
     * @return \AukroAPI\AukroApiResult
     */
    final function sellerInformation(array $auctions_arr)
    {
        $data = $this->loginInformation();        
        $params = array(
            'session-handle' => $data->session_handle_part,
            'auction-id-list' => $auctions_arr,
            'offset' => 0
        );
        $output = $this->_client->__soapCall('doMyContact', $params);
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
    
    /**
     * Pro samostatné vystavení položky na aukci!
     * @param array $fieldsArr
     * @throws AukroApiException
     * @return \AukroAPI\AukroApiResult
     */
    final function newAuctionCreate(array $fieldsArr)
    {
        $testArr = array(
            'fid' => 0,
            'fvalue-string' => '',
            'fvalue-int' => 0,
            'fvalue-float' => 0,
            'fvalue-image' => 0,
            'fvalue-datetime' => 0,
            'fvalue-date' => 0,
            'fvalue-range-int' => 0,
            'fvalue-range-float' => 0,
            'fvalue-range-date' => 0
            );
        
        foreach ($fieldsArr as $arr)
        {
            if(array_diff_key($arr, $testArr))
            {
                throw new AukroApiException('Invalid key in function . ' . __FUNCTION__);
            }
        }
        
        $data = $this->loginInformation();
        $params = array(
            'session-handle' => $data->session_handle_part,
            'fields' => $fieldsArr);
        
        $output = $this->_client->__soapCall('doNewAuctionExt', $params);
        
        return new AukroApiResult($output);
    }
    
    /**
     * Vypiše detail konkrétní aukce
     * @param type $auction_id
     * @return \AukroAPI\AukroApiResult
     */
    final function showAuctionInfo($auction_id)
    {
        $data = $this->loginInformation();
        $params = array(
            'session-handle' => $data->session_handle_part,
            'item-id' => $auction_id);
        $output = $this->_client->__soapCall('doShowItemInfoExt', $params); 
        
        return new AukroApiResult($output);
    }
    
    /**
     * Aktuální stav účtu
     * @return \AukroAPI\AukroApiResult
     */
    final function acontInfo()
    {
        $data = $this->loginInformation();
        $params = array(
            'session-handle' => $data->session_handle_part);
        $output = $this->_client->__soapCall('doMyBilling', $params); 
        
        return new AukroApiResult($output);        
    }
}