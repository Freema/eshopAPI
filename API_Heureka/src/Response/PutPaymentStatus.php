<?php
namespace HeurekaAPI\Response;

/**
 * Description of OrderStatus
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class PutPaymentStatus extends Response {
    /** @var boolean */
    private $status;
    
    /** @param array $response */
    function __construct($response) {
        
        if(!is_null($response))
        {
            $this->status      = $response['status'];

            $this->setAll(get_object_vars($this));
        }
    }
    
    /**
     * true pokud bylo vše správně nastaveno
     * 
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
