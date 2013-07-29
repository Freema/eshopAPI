<?php
namespace HeurekaAPI\Response;

/**
 * Description of GetOrderStatus
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class GetOrderStatus extends Response {
    
    /** @var integer */
    private $order_id;
    
    /** @var integer */
    private $status;
    
    /** @var integer */
    private $internal_id;
    
    /** @param array $response */
    function __construct($response) {
        
        if(!is_null($response))
        {
            $this->order_id           = $response['order_id'];
            $this->status             = $response['status'];
            $this->internal_id        = $response['internal_id'];

            $this->setAll(get_object_vars($this));
        }
    }
    
    /**
     * číslo objednávky
     *  
     * @return integer
     */
    public function getOrderId()
    {
        return $this->order_id;
    }
    
    /**
     *  stav (čísleník)
     *   1 	zaplaceno
     *  -1 	nezaplaceno
     * 
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * interní číslo objednávky v systému Heureky (s tímto číslem komunikujeme se zákazníkem)
     * 
     * @return integer
     */
    public function getInternalId()
    {
        return $this->internal_id;
    }
}