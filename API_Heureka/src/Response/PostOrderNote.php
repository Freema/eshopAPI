<?php
namespace HeurekaAPI\Response;

/**
 * Description of PostOrderNote
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class PostOrderNote extends Response {
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
     * true - zda se poznámka uložila
     * 
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}