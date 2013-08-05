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
    function TransactionsId(array $auctions_arr);
}

interface IFormHelper {
    
    function __construct(Api $client);
}