<?php
require_once 'TestCase.php';

/**
 * Description of ApiTest
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class ApiTest extends TestCase {
    
    public function testMethotGetApiVerReturnObj()
    {
        $output = $this->bootstrap->apiVerInfo();
        $this->assertInstanceOf('AukroAPI\AukroApiResult', $output);     
    }
    
    public function testMethotSellFormFields()
    {
        $output = $this->bootstrap->sellFormFields(1801);
        $this->assertInstanceOf('AukroAPI\AukroApiResult', $output);     
    }
    
    public function testMethodGetCartData()
    {
        $output = $this->bootstrap->cartData();
        $this->assertInstanceOf('AukroAPI\AukroApiResult', $output);     
    }
}