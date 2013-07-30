<?php

require_once dirname(__FILE__) . '/../Api.php';
$status = new \HeurekaAPI\Api('validate');


function dump($var)
{
    if(!extension_loaded('xdebug'))
    {
        echo '<pre>' . var_dump($var) . '</pre>';
    }
    else
    {
        var_dump($var);
    }
}
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
$response0 = $status->getPaymentStatus()->setOrderId(22)->execute();
echo '<div><strong>Error:</strong></div>';
dump($status->getContainer()->hasError());
echo '</pre>';
echo '<div><div><strong>Response:</strong></div>';
dump($response0->getDate());
dump($response0->getOrderId());
dump($response0->getStatus());
dump($response0->fetchAll());
dump($response0->fetchAll(TRUE));
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
$response1 = $status->putOrderStatus()
                    ->setOrderId(22)
                    ->setStatus(1)
                    ->setTracnkingUrl('http://www.exmaple.com/?id=101010&transport')
                    ->setNote('test')
                    ->setExpectDeliver('2013-01-10')
                    ->execute();

echo '<div><strong>Error:</strong></div>';
dump($status->getContainer()->hasError());
echo '<div><div><strong>Response:</strong></div>';
dump($response1->getStatus());
dump($response1->fetchAll());
dump($response1->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>PUT payment/status</h2>
    <p>Nastavení stavu platby na Heurece.</p>
    <p>Tato metoda slouží k nastavení platby při dobírce nebo platbě v hotovosti na pobočce obchodu. </p>
<?php
$response2 = $status->putPaymentStatus()
                     ->setOrderId(22)
                     ->setStatus(1)
                     ->setDate('2013-01-10') // akceptuje i DateTime object 
                     ->execute();


echo '<div><strong>Error:</strong></div>';
dump($status->getContainer()->hasError());
echo '<div><div><strong>Response:</strong></div>';
dump($response2->getStatus());
dump($response2->fetchAll());
dump($response2->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>GET order/status</h2>
    <p>Informace o stavu objednávky a interním čísle objednávky na Heurece.</p>
<?php
$response3 = $status->getOrderStatus()
                     ->setOrderId(22)
                     ->execute();

echo '<div><strong>Error:</strong></div>';
dump($status->getContainer()->hasError());

echo '<div><div><strong>Response:</strong></div>';
dump($response3->getStatus());
dump($response3->getOrderId());
dump($response3->getInternalId());
dump($response3->fetchAll());
dump($response3->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>GET stores</h2>
    <p>Informace o pobočkách / výdejních místech, které má obchod uložené na Heurece. </p>
<?php
$response4 = $status->getStores()->execute();

echo '<div><strong>Error:</strong></div>';
dump($status->getContainer()->hasError());

echo '<div><div><strong>Response:</strong></div>';

/* @var $store \HeurekaAPI\Response\GetStore */
foreach ($response4 as $store)
{
    dump($store->getId());
    dump($store->getCity());
    dump($store->getName());
    dump($store->getType());
    dump($store->fetchAll());
    dump($store->fetchAll(TRUE));
}
echo '</div>';
echo '<hr />';
?>
    <h2>GET shop/status</h2>
    <p>Informace o aktivaci obchodu v Košíku.</p>
    <p> Slouží k zjištění zda je obchod spuštěn v Košíku či nikoliv. Pokud je Košík vypnutý z důvodu chyby v API nebo nějaké procesní chyby, je o tom napsáno v parametru message.

Informace o aktivaci / dekativaci jsou vždy na 30 minut uložné ve vyrovnávací paměti (cache). Pokud testujete stav obchodu pomocí cronu zvolte interval 30 minut a více. </p>
<?php
$response5 = $status->getShopStatus()->execute();

echo '<div><strong>Error:</strong></div>';
dump($status->getContainer()->hasError());

echo '<div><div><strong>Response:</strong></div>';

dump($response5->getCreated());
dump($response5->getMessage());
dump($response5->getStatus());
dump($response5->fetchAll());
dump($response5->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>POST order/note</h2>
    <p> Zaslání poznámky, které obchod vytvořil při procesu vyřizování objednávky.
Tyto poznámky se zobrazují zákazníkovi u objednávky v jeho profilu. </p>
<?php
$response6 = $status->postOrderNote()->setOrderId(22)->setNote('test')->execute();

echo '<div><strong>Error:</strong></div>';
dump($status->getContainer()->hasError());
echo '<div><div><strong>Response:</strong></div>';

dump($response6->getStatus());
dump($response6->fetchAll());
dump($response6->fetchAll(TRUE));
echo '</div>';
echo '<hr />';
?>
    <h2>POST order/invoice</h2>
    <p> Zaslaní faktury (dokladu) k objednávce.

Obchody, které posílají faktury zákazníkům v elektronické podobě, ji musí zaslat také Heurece, tak aby je bylo možné opětovně poslat nebo umožnit jejich stažení v přehledu objednávek.

Maximální velikost souboru s fakturou je 3 MB a souboru musí být v PDF.

Tato metoda předpokládá multipart data u parametru file. POST požadavek by měl mít nastaven Content-type na multipart / form-data. </p>
<?php
$response7 = $status->postOrderInvoice()->setInvoiceFile('test.pdf')->setOrderId(22)->execute();


echo '<div><strong>Error:</strong></div>';
dump($status->getContainer()->hasError());
echo '<div><div><strong>Response:</strong></div>';

dump($response7->getStatus());
dump($response7->fetchAll());
dump($response7->fetchAll(TRUE));

echo '</div>';

?>    
</body>
</html>