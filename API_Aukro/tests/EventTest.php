<?php

require 'TestCase.php';

/**
 * Description of Response
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class EventTest extends TestCase {
    
    public function testIfMethotGetApiVerInfoWork()
    {
        $output = $this->bootstrap->getApiVerInfo();
        $this->assertInstanceOf('AukroAPI\AukroApiResult', $output);     
    }
}