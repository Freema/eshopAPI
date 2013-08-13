<?php
require_once dirname(__FILE__) . '/../AukroApi.php';
$client = new AukroAPI\Api('Freema1','Freeman25', 'bbc47010', 228);
$array = array_merge($_POST, $_FILES);
var_dump($array);

$fieldsArr = $client->getBaseFormHelper()->sendNewAuction($_POST);

var_dump($fieldsArr);
//$client->login()->newAuctionCreate($fieldsArr);