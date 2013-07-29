<?php
namespace HeurekaAPI\Response;

/**
 * Description of PaymentStatus
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class GetPaymentStatus extends Response {
    
    /** @var integer */
    private $order_id;
    
    /** @var integer */
    private $status;
    
    /** @var \DateTime */
    private $date;
    
    /** @param array $response */
    function __construct($response) {
        
        if(!is_null($response))
        {
            $this->order_id    = $response['order_id'];
            $this->status      = $response['status'];
            $this->date        = new \DateTime($response['date']);

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
     * datum změny stavu
     * 
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}