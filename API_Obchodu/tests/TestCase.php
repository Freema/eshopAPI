<?php
require __DIR__ . '/../loader.php';

/**
 * Description of TestCase
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class TestCase extends PHPUnit_Framework_TestCase {
    
    /** @var Bootstrap */
    public $bootstrap = NULL;
    
    public function __construct()
    {
        $this->bootstrap = new Bootstrap();
    }      
}
