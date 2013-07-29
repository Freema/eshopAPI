<?php
namespace HeurekaAPI\Response;

/**
 * Description of GetShopStatus
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class GetShopStatus extends Response {
    
    /** @var boolean */
    private $status;
    
    /** @var string */
    private $message;
    
    /** @var \DateTime */
    private $created;
    
    /** @param array $response */
    function __construct($response) {
        
        if(!is_null($response))
        {
            $this->status           = $response['status'];
            if($response['status'] == FALSE)
            {
                $this->message      = $response['error']['message'];
                $this->created      = new \DateTime($response['error']['created']);
            }

            $this->setAll(get_object_vars($this));
        }
    }
    
    /**
     * true pokud je obchod zapnutý
     *  
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * interní číslo objednávky v systému Heureky (s tímto číslem komunikujeme se zákazníkem)
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * čas kdy byl obchod deaktivován (YYYY-MM-DD HH:MM:SS)
     * 
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}