<?php

require 'TestCase.php';

/**
 * Description of Response
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class ResponseTest extends TestCase {
    

    public function testCheckIfOrderCancelReturnArray()
    {
        $prm = new OrderCancel();
        $prm->addCancelResponse(TRUE);
        
        $container = $this->bootstrap->putOrderCancel();
        $this->helperContainer($container, $prm);
    }
    
    public function testCheckIfOrderSendReturnArray()
    {
        $prm = new OrderSend();
        $prm->addSendResponse(1, 1, 1);
        
        $container = $this->bootstrap->postOrderSend();
        $this->helperContainer($container, $prm);
    }

    public function testCheckIfOrderStatusReturnArray()
    {
        $prm = new OrderStatus();
        $prm->addStatus(1, 1);
        
        $container = $this->bootstrap->putPaymentStatus();
        $this->helperContainer($container, $prm);
    }

    public function testCheckIfPaymentDeliveryReturnArray()
    {
        $prm = new PaymentDelivery();
        $prm->addTransport(1, 'type', 'name', 100.00, 'description');
        $prm->addStore(1, 'store');
        $prm->addPayment(1, 'type', 'name', 100.00);
        $prm->addBinding(1, 1, 1);
        
        $container = $this->bootstrap->getPamentDelivery();
        $this->helperContainer($container, $prm);
    }


    public function testCheckIfPaymentStatusReturnArray()
    {
        $prm = new PaymentStatus();
        $prm->addStatusResponse(1);
        
        $container = $this->bootstrap->putPaymentStatus();
        $this->helperContainer($container, $prm);
    }
    
    public function testCheckIfProductsAvailabilityReturnArray()
    {
        $prm = new ProductsAvailability(1,1,1,1,'name', 100.00);
        
        $prm->addParam(1, 1, 'name', 'unit');
        $prm->addRelated('title');
        $prm->addValue(1, 'default', 'value', 1000.00);
        
        $container = $this->bootstrap->getProductsAvailability();
        $this->helperContainer($container, $prm);
    }
    
    /**
     * @param Container $container
     * @param object $prm
     */
    public function helperContainer(Container $container, $prm)
    {
        $container->add($prm);
        $this->bootstrap->getContainer()->jsonEncoding = FALSE;
        $response = $container->getResponse();
        
        $this->assertInternalType('array', $response);        
    }
}