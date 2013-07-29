<?php
namespace HeurekaAPI\Response;

use stdClass;

/**
 * Description of Response
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

abstract class Response {
    
    /**
     * @var array
     */
    private $_getAll;
    
    /**
     * 
     * get All vars
     * 
     * @return array|stdClass
     */
    public function fetchAll($isObject = FALSE)
    {
        if ($isObject === TRUE)
        {
            if(!empty($this->_getAll))
            {
                $object = new stdClass();

                foreach ($this->_getAll as $key => $value)
                {
                    $object->$key = $value;
                }

                $return = $object;
            }
            else
            {
                $return = NULL;            
            }
        }
        else
        {
            $return = $this->_getAll;
        }
        
        return $return;
    }
    
    /**
     * 
     * @param array $vars
     * @return \API_Heureka\Response\Response
     */
    protected function setAll(array $vars)
    {
        $this->_getAll = $vars;
        return $this;
    }
}