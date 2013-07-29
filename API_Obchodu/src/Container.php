<?php
/**
 * Description of Container
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class Container {

    /**
     * @var array | string
     */
    private $_response;
    
    /**
     * @var string name of response object
     */
    private $_string;
    
    /**
     * @var string
     */
    private $_arrayHelperName;
    
    /**
     * @var bool
     */
    public $_isEmpty = TRUE;
    
    /**
     * @var bool active/deactive jason encoding of the response var
     */
    public $jsonEncoding = TRUE;
    
    /**
     * @var string 
     */
    private $_helperPrefix;
    
    /**
     * Set prefix
     * 
     * @param type $string
     */
    public function setPrefixArray($string)
    {
        $this->_string = (string) $string;
        $this->_response[$string] = array();
    }
    
    public function setHelperPrefixName($prefix)
    {
        $this->_helperPrefix = $prefix;
    }

    /**
     * Add helper object for array creating
     *  
     * @param object $helper
     * @return \Container
     * @throws Exception
     */
    public function add($helper)
    {
        if(!is_object($helper))
        {
            throw new Exception('Container only accepts object');
        }
        
        if(get_class($helper) == $this->_helperPrefix)
        {
            if(empty($this->_string))
            {
               $this->_response = $helper->getParams();
            }
            else
            {
               $this->_response[$this->_string][] = $helper->getParams();
            }
            return $this;
        }
        else
        {
            throw new Exception('You can use only object, that is in Heureka api. Object name: '.$this->_classHelperName.' is invalid');
        }
    }
    
    /**
     * Check if is response in js
     * @return bool
     */
    public function isResponseEmpty()
    {
        return $this->_isEmpty;
    }
    
    /**
     * Response json object
     * 
     * @return string
     */
    public function getResponse()
    {
        if(!is_null($helper = $this->_arrayHelperName)){
            $this->$helper();
        }   
        
        if($this->jsonEncoding == TRUE){
            $this->json_encode();
        }
        
        return $this->_response;
    }
    
    /**
     * @return \Container
     */
    protected function json_encode()
    {
        if(PHP_VERSION_ID > 50400)
        {
            $this->_response = json_encode($this->_response, JSON_UNESCAPED_UNICODE );
        }
        else
        {
            $this->_response = json_encode($this->_response);
        }
        
        return $this;
    }
    
    protected function priceSum()
    {
        $this->_response['priceSum'] = 0;
        foreach ($this->_response['products'] as $product)
        {
            $this->_response['priceSum'] = $this->_response['priceSum'] + $product['priceTotal'];            
        }
    }
}
