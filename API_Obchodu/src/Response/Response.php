<?php
/**
 * Description of response
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

abstract class Response {

    /**
     * @var array
     */
    protected $_parameters;    
    
    /**
    * @return array
    */
    public function getParams()
    {
        return $this->_parameters;
    }  
    
    protected function json_encode_unicode($data) {
        return preg_replace_callback('/(?<!\\\\)\\\\u([0-9a-f]{4})/i', function ($m) {
                    $d = pack("H*", $m[1]);
                    $r = mb_convert_encoding($d, "UTF8", "UTF-16BE");
                    return $r !== "?" && $r !== "" ? $r : $m[0];
                }, json_encode($data)
        );
    }    
}
