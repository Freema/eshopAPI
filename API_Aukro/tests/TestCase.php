<?php

use AukroAPI\Api;
require_once __DIR__ . '/../AukroApi.php';

/**
 * Description of TestCase
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class TestCase extends PHPUnit_Framework_TestCase {
    
    /** @var Api */
    public $bootstrap = NULL;
    
    /**
     *  connection constants
     */
    const USER_NAME = 'Freema25';
    const USER_PASS = 'Freeman25';
    const API_KEY = 'bbc47010';  
    
    /**
     * setUp test case
     */
    protected function setUp() {
        $username = self::USER_NAME;
        $password = self::USER_PASS;
        $apiKey = self::API_KEY;
        if (!extension_loaded('soap')) {
            $this->markTestSkipped(
              'The SOAP extension is not available.'
            );
        }
        if (!extension_loaded('openssl')) {
            $this->markTestSkipped(
              'The opensl extension is not available.'
            );
        }
        if(empty($username) || empty($password) || empty($apiKey))
        {
            $this->markTestSkipped('Constatnt USER_NAME or USER_PASS or API_KEY is empty!');
        }
        $this->bootstrap = new Api($username,  $password, $apiKey);
    }
}