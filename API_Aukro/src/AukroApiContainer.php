<?php
namespace AukroAPI;

use SoapClient;
/**
 * Description of AukroApiContainer
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class AukroApiContainer extends SoapClient {

    protected $_webApiFunction = array(
       'doCancelTransaction',
        'doGetFilledPostBuyForms',
        'doGetMyAddresses',
        'doGetPaymentMethods',
        'doGetPostBuyItemInfo',
        'doGetRelatedItems',
        'doGetShipmentDataForRelatedItems',
        'doSendPostBuyForm',
        'doGetShopCatsData',
        'doSellSomeAgainInShop',
        'doAddToBlackList',
        'doGetBlackListUsers',
        'doRemoveFromBlackList',
        'doBidItem',
        'doRequestCancelBid',
        'doGetCategoryPath',
        'doGetCatsData',
        'doGetCatsDataCount',
        'doGetCatsDataLimit',
        'doCancelRefundForms',
        'doCancelRefundWarnings',
        'doGetRefundFormsStatuses',
        'doGetRefundReasons',
        'doGetRefundTransactions',
        'doSendRefundForms',
        'doSendReminderMessages',
        'doQueryAllSysStatus',
        'doQuerySysStatus',
        'doGetDeals',
        'doMakeDiscount',
        'doMakeDiscountByCoupon',
        'doGetSiteJournal',
        'doGetSiteJournalDeals',
        'doGetSiteJournalDealsInfo',
        'doGetSiteJournalInfo',
        'doFeedback',
        'doFeedbackMany',
        'doGetFeedback',
        'doGetMySellRating',
        'doGetSellRatingReasons',
        'doGetWaitingFeedbacks',
        'doGetWaitingFeedbacksCount',
        'doMyFeedback2',
        'doMyFeedback2Limit',
        'doGetPaymentData',
        'doMyBilling',
        'doMyBillingItem',
        'doGetAdminUserLicenceDate',
        'doGetUserLicenceDate',
        'doSetUserLicenceDate',
        'doCheckItemDescription',
        'doCheckNewAuctionExt',
        'doGetSellFormFieldsExt',
        'doGetSellFormFieldsExtLimit',
        'doGetSellFormFieldsForCategory',
        'doNewAuctionExt',
        'doSellSomeAgain',
        'doVerifyItem',
        'doLogin',
        'doLoginEnc',
        'doAddDescToItems',
        'doCancelBidItem',
        'doChangeItemFields',
        'doChangePriceItem',
        'doChangeQuantityItem',
        'doFinishItem',
        'doFinishItems',
        'doGetFavouriteCategories',
        'doGetFavouriteSellers',
        'doGetMyBidItems',
        'doGetMyFutureItems',
        'doGetMyNotSoldItems',
        'doGetMyNotWonItems',
        'doGetMySellItems',
        'doGetMySoldItems',
        'doGetMyWatchedItems',
        'doGetMyWatchItems',
        'doGetMyWonItems',
        'doMyAccount2',
        'doMyAccountItemsCount',
        'doRemoveFromWatchList',
        'doGetServiceInfo',
        'doGetServiceInfoCategories',
        'doAddToWatchList',
        'doGetBidItem2',
        'doGetItemFields',
        'doGetItemsInfo',
        'doSendEmailToUser',
        'doShowItemInfoExt',
        'doGetMyIncomingPayments',
        'doGetMyIncomingPaymentsRefunds',
        'doGetMyPayments',
        'doGetMyPaymentsInfo',
        'doGetMyPaymentsRefunds',
        'doGetMyPayouts',
        'doRequestPayout',
        'doRequestSurcharge',
        'doGetSellFormAttribs',
        'doGetSpecialItems',
        'doSearch',
        'doShowCat',
        'doGetMyCurrentShipmentPriceType',
        'doGetShipmentPriceTypes',
        'doSetShipmentPriceType',
        'doCreateItemTemplate',
        'doGetItemTemplates',
        'doGetServiceTemplates',
        'doRemoveItemTemplates',
        'doAddPackageInfoToPostBuyForm',
        'doGetMessageToBuyer',
        'doGetPostBuyData',
        'doGetPostBuyFormsDataForBuyers',
        'doGetPostBuyFormsDataForSellers',
        'doGetTransactionsIDs',
        'doMyContact',
        'doGetMyData',
        'doGetUserID',
        'doGetUserItems',
        'doGetUserLogin',
        'doShowUser',
        'doShowUserPage',
        'doCheckExternalKey',
        'doGetCountries',
        'doGetShipmentData',
        'doGetSitesFlagInfo',
        'doGetSitesInfo',
        'doGetStatesInfo',
        'doGetSystemTime',
);
    
    /**
     * @param string $wsdl
     */
    public function __construct($wsdl) {
        parent::SoapClient($wsdl);
    }
    
    function __call($function_name, $arguments = NULL) {

        if(array_search($function_name, $this->_webApiFunction))
        {
            try
            {
                if(is_null($arguments))
                {
                    $output = $this->__soapCall($function_name);
                }
                else
                {
                    $output = $this->__soapCall($function_name, $arguments[0]);
                }
                return new AukroApiResult($output);
            } catch (AukroApiException $e) {
                 print_r("{$e->faultcode} - {$e->faultstring}");
            }            
        }
        else
        {
            throw new AukroApiException('Unknown web api function.');
        }
    }
}
