<?php

require_once dirname(__FILE__) . '/../Api.php';

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
</head>

<body>

<div class="container">
    <h1 class="page-header">Ukázka použiti API_HEUREKA</h1>

    <h2>GET payment/status</h2>

<?php
$status = new \HeurekaAPI\Api('validate');
$response = $status->getPaymentStatus()->setOrderId(22)->execute();
echo '<div><strong>Error:</strong></div>';
var_dump($status->getContainer()->hasError());
echo '</pre>';
echo '<div><div><strong>Response:</strong></div>';
var_dump($response->getDate());
var_dump($response->getOrderId());
var_dump($response->getStatus());
var_dump($response->fetchAll());
var_dump($response->fetchAll(TRUE));
echo '</div>';
?>
    <h2>PUT order/status</h2>
    <p>
     Nastavení stavu objednávy na Heurece.
    </p>
    <p>
    Je důležité, aby každá změna objednávky byla přenesena zpět do Heureky. Jenom tak je možné zákazníkům zobrazit v jakém stavu se nachází jejich objednávka.     
    </p>
<?php
echo '<hr />';
$status1 = new \HeurekaAPI\Api('validate');
$response1 = $status1->putOrderStatus()
                     ->setOrderId(22)
                     ->setStatus(1)
                     ->setTracnkingUrl('http://www.exmaple.com/?id=101010&transport')
                     ->setNote('test')
                     ->setExpectDeliver('2013-01-10')
                     ->execute();

echo '<div><strong>Error:</strong></div>';
var_dump($status1->getContainer()->hasError());
echo '<div><div><strong>Response:</strong></div>';
var_dump($response1->getStatus());
var_dump($response1->fetchAll());
var_dump($response1->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>PUT payment/status</h2>
    <p>Nastavení stavu platby na Heurece.</p>
    <p>Tato metoda slouží k nastavení platby při dobírce nebo platbě v hotovosti na pobočce obchodu. </p>
<?php
$status2 = new \HeurekaAPI\Api('validate');
$response2 = $status2->putPaymentStatus()
                     ->setOrderId(22)
                     ->setStatus(1)
                     ->setDate('2013-01-10') // akceptuje i DateTime object 
                     ->execute();


echo '<div><strong>Error:</strong></div>';
var_dump($status2->getContainer()->hasError());
echo '<div><div><strong>Response:</strong></div>';
var_dump($response2->getStatus());
var_dump($response2->fetchAll());
var_dump($response2->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>GET order/status</h2>
    <p>Informace o stavu objednávky a interním čísle objednávky na Heurece.</p>
<?php
$status3 = new \HeurekaAPI\Api('validate');
$response3 = $status3->getOrderStatus()
                     ->setOrderId(22)
                     ->execute();

echo '<div><strong>Error:</strong></div>';
var_dump($status3->getContainer()->hasError());

echo '<div><div><strong>Response:</strong></div>';
var_dump($response3->getStatus());
var_dump($response3->getOrderId());
var_dump($response3->getInternalId());
var_dump($response3->fetchAll());
var_dump($response3->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>GET stores</h2>
    <p>Informace o pobočkách / výdejních místech, které má obchod uložené na Heurece. </p>
<?php
$status4 = new \HeurekaAPI\Api('validate');
$response4 = $status4->getStores()->execute();

echo '<div><strong>Error:</strong></div>';
var_dump($status4->getContainer()->hasError());

echo '<div><div><strong>Response:</strong></div>';

/* @var $store \HeurekaAPI\Response\GetStore */
foreach ($response4 as $store)
{
    var_dump($store->getId());
    var_dump($store->getCity());
    var_dump($store->getName());
    var_dump($store->getType());
    var_dump($store->fetchAll());
    var_dump($store->fetchAll(TRUE));
}
echo '</div>';
echo '<hr />';
?>
    <h2>GET shop/status</h2>
    <p>Informace o aktivaci obchodu v Košíku.</p>
    <p> Slouží k zjištění zda je obchod spuštěn v Košíku či nikoliv. Pokud je Košík vypnutý z důvodu chyby v API nebo nějaké procesní chyby, je o tom napsáno v parametru message.

Informace o aktivaci / dekativaci jsou vždy na 30 minut uložné ve vyrovnávací paměti (cache). Pokud testujete stav obchodu pomocí cronu zvolte interval 30 minut a více. </p>
<?php
$status5 = new \HeurekaAPI\Api('validate');
$response5 = $status5->getShopStatus()->execute();

echo '<div><strong>Error:</strong></div>';
var_dump($status5->getContainer()->hasError());

echo '<div><div><strong>Response:</strong></div>';

var_dump($response5->getCreated());
var_dump($response5->getMessage());
var_dump($response5->getStatus());
var_dump($response5->fetchAll());
var_dump($response5->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>POST order/note</h2>
    <p> Zaslání poznámky, které obchod vytvořil při procesu vyřizování objednávky.
Tyto poznámky se zobrazují zákazníkovi u objednávky v jeho profilu. </p>
<?php
$status6 = new \HeurekaAPI\Api('validate');
$response6 = $status6->postOrderNote()->setOrderId(22)->setNote('test')->execute();

echo '<div><strong>Error:</strong></div>';
var_dump($status6->getContainer()->hasError());
echo '<div><div><strong>Response:</strong></div>';

var_dump($response6->getStatus());
var_dump($response6->fetchAll());
var_dump($response6->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>POST order/invoice</h2>
    <p> Zaslaní faktury (dokladu) k objednávce.

Obchody, které posílají faktury zákazníkům v elektronické podobě, ji musí zaslat také Heurece, tak aby je bylo možné opětovně poslat nebo umožnit jejich stažení v přehledu objednávek.

Maximální velikost souboru s fakturou je 3 MB a souboru musí být v PDF.

Tato metoda předpokládá multipart data u parametru file. POST požadavek by měl mít nastaven Content-type na multipart / form-data. </p>
<?php
$status7 = new \HeurekaAPI\Api('validate');

$response7 = $status7->postOrderInvoice()->setInvoiceFile('test.pdf')->setOrderId(22)->execute();


echo '<div><strong>Error:</strong></div>';
var_dump($status7->getContainer()->hasError());
echo '<div><div><strong>Response:</strong></div>';

var_dump($response7->getStatus());
var_dump($response7->fetchAll());
var_dump($response7->fetchAll(TRUE));

echo '</div>';

?>    
</body>
</html>
