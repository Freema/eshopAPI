<?php
/**
 * 
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class Bootstrap {
    
    const HELPER_PRODUCT_AVAILABILITY   = 'ProductsAvailability';
    const HELPER_PAYMENT_DELIVERY       = 'PaymentDelivery';
    const HELPER_ORDER_STATUS           = 'OrderStatus';
    const HELPER_ORDER_SEND             = 'OrderSend';
    const HELPER_ORDER_CANCEL           = 'OrderCancel';
    const HELPER_PAYMENT_STATUS         = 'PaymentStatus';
    
    /**
     * @var integer
     */
    private $_apiVersion = 1;
    
    /**
     * @var IContainer 
     */
    private $_container; 
    
    /**
     * @var array 
     */
    private $_errors = array();

    /**
     * @param integer $version
     */
    public function setApiVersion($version)
    {
        if(is_integer($version))
        {
            $this->_apiVersion = $version;
            return $this;
        }
        else
        {
            throw new InvalidArgumentException('stApiVersion function only accepts integers. Input was:'. $version);
        }
    }
    
    /**
     * @return IContainer
     * @throws HeurekaApiException
     */
    public function getContainer()
    {
        if($this->_container == NULL)
        {
            throw new HeurekaApiException('Container not set!');
        }
        
        return $this->_container;
    }
    
    /**
     * Check if there is no error
     * 
     * @return boolean
     */
    public function isError()
    {
        if(empty($this->_errors))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    /**
     * get errors messages
     * 
     * @return array
     */
    public function getError()
    {
        return $this->_errors;
    }
    
    /**
     * @var $products accept only array, imput validation
     * @return \Container
     */
    public function getProductsAvailability($products = NULL)
    {
        if(!empty($products)) {
            foreach ($products as $product)
            {
                if($id = self::validateId($product)) $this->_errors[] = $id;
                if($count = self::validateCount($product)) $this->_errors[] = $count;
            }
            
        }
        
        $container = new Container();
        $container->setPrefixArray('products');
        $container->setHelperPrefixName(self::HELPER_PRODUCT_AVAILABILITY);
        $this->_container = $container;
        return $container;
    }
    
    /**
     * @var $products accept only array, imput validation
     * @return \Container
     */
    public function getPamentDelivery($products = NULL)
    {
        if(!empty($products)) {
            foreach ($products as $product)
            {
                if($id = self::validateId($product)) $this->_errors[] = $id;
                if($count = self::validateCount($product)) $this->_errors[] = $count;
            }
        }        
        
        $container = new Container();
        $container->setHelperPrefixName(self::HELPER_PAYMENT_DELIVERY);
        $this->_container = $container;
        return $container;
    }
    
    /**
     * @return \Container
     */
    public function getOrderStatus($order_id = NULL)
    {
        if(!empty($order_id)) {
            if($order_id = self::validateOrderId($order_id)) $this->_errors[] = $order_id;
        } 
        
        $container = new Container();
        $container->setHelperPrefixName(self::HELPER_ORDER_STATUS);
        $this->_container = $container;
        return $container;
    }
    
    /**
     * @return \Container
     */
    public function postOrderSend()
    {
        $container = new Container();
        $container->setHelperPrefixName(self::HELPER_ORDER_SEND);        
        $this->_container = $container;
        return $container;
    }
    
    /**
     * @return \Container
     */
    public function putOrderCancel($params = NULL)
    {
        if(!empty($params)) {
           if($order_id = self::validateOrderId($params)) $this->_errors[] = $order_id;
        }
        
        $container = new Container();
        $container->setHelperPrefixName(self::HELPER_ORDER_CANCEL);
        $this->_container = $container;
        return $container;        
    }
    
    /**
     * @return \Container
     */
    public function putPaymentStatus($params = NULL)
    {
        if(!empty($params)) {
            if($order_id = self::validateOrderId($params)) $this->_errors[] = $order_id;
        }        
        
        $container = new Container();
        $container->setHelperPrefixName(self::HELPER_PAYMENT_STATUS);
        $this->_container = $container;
        return $container;        
    }
    
    /** VALIDATION! **/
    
    /**
     * validate id reference
     * 
     * @param array $id
     */
    protected static function validateId(array $id)
    {
        if(!isset($id['id'])){
           return array('code' => 404, 'msg' => 'ID is not set.');
        }  else {
            if(empty($id['id'])) return array('code' => 404, 'msg' => 'ID is empty.');
        }
        
        return false;
    }
    
    protected static function validateOrderId($orderId)
    {
        if(!isset($orderId['order_id'])){
           return array('code' => 404, 'msg' => 'Order ID is not set.');
        }  else {
            if(empty($orderId['order_id'])) return array('code' => 404, 'msg' => 'Order ID is empty.');
        }
        
        return false;        
    }

    /**
     * validate count reference
     * 
     * @param array $count
     */
    protected static function validateCount(array $count)
    {
        if(!isset($count['count'])){
            return array('code' => 404, 'msg' => 'Order Count is not set.');
        }  else {
            if(empty($count['count'])) return array('code' => 404, 'msg' => 'Order Count is empty or is null.');
            if($count['count'] < 0) return array('code' => 404, 'msg' => 'Count can not be less than 0');
        }
        
        return false;
    }
}

class HeurekaApiException extends Exception {};
