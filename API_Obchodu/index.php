<?php
require_once 'loader.php';
require_once dirname(__FILE__) . '/router/RestRoute.php';

if (!file_exists(__DIR__ . '/dibi/dibi/dibi.php')) {
    echo "Install Dibi using `composer update --dev`\n";
    exit(1);
}
else
{
    require_once __DIR__ . '/dibi/dibi/dibi.php';    
}

$route = new RestRoute();

if(RestRoute::detectDebugMode())
{
    $conect = array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => '',
        'database'  => 'heurekaApiTest',
        'charset'   => 'utf8',
        'profiler'  => TRUE,
    );
}
else
{

}

$connection = new DibiConnection($conect);

$route->add('/', function() use ($connection) {
    
    $connect = $connection->isConnected();
    
    echo json_encode(array(
        'Status:' => RestRoute::detectDebugMode() ? 'development' : 'product',
        'DB' => $connect,
        ));

});

$route->add('/api/1/products/availability', function($products) use ($connection) {
    
    if(!empty($products))
    {
        $bootstrap = new Bootstrap();
        $action = $bootstrap->getProductsAvailability($products);
        
        if($bootstrap->isError())
        {
            throw new BadRequestException($bootstrap->getError(), 404);
        }
        
        foreach ($products as $product)
        {
            $data = $connection->query('SELECT * FROM [products] WHERE [item_id] = %i', $product['id'])->fetch();
            
            if($data)
            {
                $prm = new ProductsAvailability(
                        $data->item_id,
                        $product['count'],
                        $data->available,
                        $data->delivery,
                        $data->name,
                        $data->price);
                
                $products_related = $connection->query('SELECT * FROM [products_related] WHERE [products_id] = %i', $data->id);
                if($products_related) {
                    foreach ($products_related as $related)
                    {
                        $prm->addRelated($related->title);
                    }
                }                
                
                $products_params = $connection->query('SELECT * FROM [products_params] WHERE [products_id] = %i', $data->id);
                if($products_params) {
                    foreach ($products_params as $param)
                    {
                        $parameter = $prm->addParam($param->id, $param->typ, $param->name, $param->unit);
                        
                        $values = $connection->query('SELECT * FROM [products_related_values] WHERE [products_related_id] = %i', $param->id);
                        if($values)
                        {
                            foreach ($values as $value)
                            {
                                $parameter->addValue($value->id, $value->default, $value->value, $value->price);
                            }
                        }
                    }
                }
                $action->add($prm);                
            }

        }
        
        $response = $action->getResponse();
        echo $response;
    }
    else
    {
        throw new BadRequestException('Input parameters do not exist or have bad form.' ,404);
    }
}, RestRoute::METHOD_GET);

$route->add('/api/1/payment/delivery', function($products) use ($connection) {

    if(!empty($products))
    {
        $bootstrap = new Bootstrap;
        $action = $bootstrap->getPamentDelivery($products);
        
        if($bootstrap->isError())
        {
            throw new BadRequestException($bootstrap->getError(), 404);
        }

        $delivery = new PaymentDelivery();
        $transport = $connection->query('SELECT * FROM [transport]');
 
        foreach ($transport as $trans)
        {
            $delivery->addTransport(
                    $trans->id,
                    $trans->type,
                    $trans->name,
                    $trans->price,
                    $trans->description);
            
            $stores = $connection->query('SELECT * FROM [store] WHERE [transport_id] = %i', $trans->id);
            
            if($stores)
            {
                foreach ($stores as $store)
                $delivery->addStore($store->id, $store->type);
            }

            $action->add($delivery);        
        }

        $payment = $connection->query('SELECT * FROM [payment]');
        foreach ($payment as $paymen)
        {
            $delivery->addPayment($paymen->id, $paymen->type, $paymen->name, $paymen->price);
            $action->add($delivery);
        }

        $bindings = $connection->query('SELECT * FROM [binding]');
        foreach ($bindings as $binding)
        {
            $delivery->addBinding($binding->id, $binding->transport_id, $binding->payment_id);
            $action->add($delivery);
        }
        $response = $action->getResponse();
        echo $response;        
    }
    else
    {
        throw new BadRequestException('Input parameters do not exist or have bad form.' ,404);
    }    
}, RestRoute::METHOD_GET);

$route->add('/api/1/order/status', function ($order_id) use ($connection){
    
    if(!empty($order_id))
    {
        $bootstrap = new Bootstrap;
       
        $action = $bootstrap->getOrderStatus(array('order_id' => $order_id));
        
        $delivery = $connection->query('SELECT * FROM [order] WHERE [id] = %i', $order_id)->fetch();
        $status = new OrderStatus();
                
        if($delivery)
        {
            $status->addStatus($delivery->id, $delivery->status);
        }
        else
        {
            throw new BadRequestException('Objednavka nenalezena!', 400);
        }
        $action->add($status);
        $response = $action->getResponse();
        echo $response;
    }
    else
    {
        throw new BadRequestException('Input parameters do not exist or have bad form.' ,404);
    }    
}, RestRoute::METHOD_GET);

/**
 * @var $products array
 * @var $paymentOnlineType array
 * @var $customer array
 * @var $deliveryAddress array
 */
$route->add('/api/1/order/send', function ($products, $paymentOnlineType, $customer, $deliveryAddress ) use ($connection){

    //$params = $route->httpRequest();
    
    if($products)
    {
        $bootstrap = new Bootstrap;
        $action = $bootstrap->postOrderSend();
        $sendResponse = new OrderSend();

        foreach ($products as $product)
        {
            $arr = array(
                'products_id'       => 1,
                'count'             => $product['count'],
                'intenal_id'        => rand(),
                'variableSymbol'    => rand(),
                
            );
            
            try {
                $connection->query('INSERT INTO [order]', $arr);
                $id = $connection->getInsertId();
                $sendResponse->addSendResponse($id, $arr['intenal_id'], $arr['variableSymbol']);            
            } catch (Exception $exc) {
                if($exc instanceof DibiDriverException)
                {
                    throw new BadRequestException('Chyba v API připojení!', 500);
                }
            }
        }
        $action->add($sendResponse);
        $response = $action->getResponse();
        echo $response;
    }
    else
    {
       throw new BadRequestException('Input parameters do not exist or have bad form.' ,404);        
    }
}, RestRoute::METHOD_POST);

/**
 * @var $order_id integer
 * @var $reason integer
 */
$route->add('/api/1/order/cancel', function ($order_id, $reason) use ($connection) {
    
    //$params = $route->httpRequest();
    
    if($order_id)
    {
        $bootstrap = new Bootstrap;
        $action = $bootstrap->putOrderCancel();

        if($bootstrap->isError())
        {
            throw new BadRequestException($bootstrap->getError(), 404);
        }        
        
        $sendResponse = new OrderCancel();
        /**
         * tohle je na opravu!
         */
        if($reason)
        {
            $arr = array(
                'reason'    => $reason,
            );
        }
        else
        {
            $arr = array(
                'reason'    => 0,
            );
        }
        
        $update = $connection->query('UPDATE [order] SET ', $arr, 'WHERE [id] = %i', $order_id);
        if($update)
        {
            $sendResponse->addCancelResponse(TRUE);
        }
        else
        {
            $sendResponse->addCancelResponse(FALSE);
        }
        $action->add($sendResponse);
        $response = $action->getResponse();
        echo $response;           
    }
    else
    {
       throw new BadRequestException('Input parameters do not exist or have bad form.' ,404);        
    }
}, RestRoute::METHOD_PUT);

/**
 * @var $order_id integer
 * @var $status integer
 * @var $date string
 */
$route->add('/api/1/payment/status', function ($order_id, $status, $date) use ($route){
    
    if($order_id)
    {
        $bootstrap = new Bootstrap;
        $action = $bootstrap->putPaymentStatus($order_id);

        $sendResponse = new PaymentStatus();
        $sendResponse->addStatusResponse((bool) $status);
        $action->add($sendResponse);
        $response = $action->getResponse();
        echo $response;    
    }
    else
    {
       throw new BadRequestException('Input parameters do not exist or have bad form.' ,404);        
    }
}, RestRoute::METHOD_PUT);

/**
 * @var $exc BadRequestException
 */
$route->badRequest(function ($exc) {

    if($exc instanceof BadRequestException)
    {
        if($exc->getStackMessage())
        {
            $encode = $exc->getStackMessage();
        }
        else
        {
            $encode = array('id' => $exc->getCode(), 'msg' => $exc->getMessage());
        }
    }
    echo json_encode($encode);
});

$route->listen();