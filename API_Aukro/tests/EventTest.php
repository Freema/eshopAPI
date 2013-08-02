<?php
require_once 'TestCase.php';

/**
 * Description of ApiTest
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class EventTest extends TestCase {
    
    /** @var \AukroAPI\Event */
    public $bootstrap;
     
    function setUp() {
        parent::setUp();
        
        $this->bootstrap = $this->bootstrap->login();
    }
   
    public function testMethodGetLoginInformationReturnObj()
    {
        $output = $this->bootstrap->loginInformation();
        $this->assertInstanceOf('AukroAPI\AukroApiResult', $output);     
    }
    
    public function testMethodListOfSavedAuctionsReturnObj()
    {
        $output = $this->bootstrap->listOfSavedAuctions();
        $this->assertInstanceOf('AukroAPI\AukroApiResult', $output);     
    }
}