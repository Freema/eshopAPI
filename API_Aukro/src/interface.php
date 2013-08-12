<?php
namespace AukroAPI;
/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
interface IEvents {

    /**
     * Informace o přihlašeným uživateli.
     * @return AukroApiResult
     */
    function loginInformation();
   
    /**
     * Ověření zda se na učtě něco prodalo.
     * @return AukroApiResult 
     */
    function logDeals();
    
    /**
     * Získání seznamu uložených aukcí. 
     * @retrun AukroApiResult
     */
    function listOfSavedAuctions();
    
    /**
     * Získa id transakce
     * @param array $auctions_arr
     * @return \AukroAPI\AukroApiResult
     */
    function transactionsId(array $auctions_arr);
    
    /**
     * Pro samostatné vystavení položky na aukci!
     * @param array $fieldsArr
     * @throws AukroApiException
     * @return \AukroAPI\AukroApiResult
     */
    function newAuctionCreate(array $fieldsArr);
    
    /**
     * Informace vyplněné kupujícími ve formuláři
     * @param type $trans_ids
     * @return \AukroAPI\AukroApiResult
     */
    function transactionsInformation($trans_ids);  
    
    /**
     * Informace o kupujicím
     * @param array $auctions_arr
     * @return \AukroAPI\AukroApiResult
     */
     function sellerInformation(array $auctions_arr);
}

interface IFormHelper {
    
    function __construct(Api $client);
}