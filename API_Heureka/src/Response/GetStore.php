<?php
namespace HeurekaAPI\Response;
/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 *
 * @author Tomáš
 */
class GetStore extends Response{
    
    /** @var integer */
    private $id;
    
    /** @var integer */
    private $type;
    
    /** @var string */
    private $name;
    
    /** @var string */
    private $city;
    
    /** @param array $response */
    function __construct($response) {
        
        if(!is_null($response))
        {
            $this->id          = $response['id'];
            $this->type        = $response['type'];
            $this->name        = $response['name'];
            $this->city        = $response['city'];

            $this->setAll(get_object_vars($this));
        }
    }
    
    /**
     * ID pobočky / výdejního místa
     *  
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * typ pobočky / výdejního místa (čísleník)
     * 1 interní pobočka / výdejní místo obchodu
     * 2 Heureka Point
     * 
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * název
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }    
    
    /**
     * umístění
     * 
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }    
}

