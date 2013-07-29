<?php

/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 * 
 * add route:
 * 
 * $route = new RestRoute();
 * 
 * www.example.com/test?GET=1
 * $route->add('/test/', function($GET) {
 * };
 * 
 * error handle:
 * 
 * @var $ext BadRequestException
 * $route->badRequest(function ($exc){}
 */

class RestRoute
{
    /** method */
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';
    
    /**
     * Prefix for trim funciton
     * 
     * @var string
     */
    private $_trim = '/\^$';
    
    /**
     * @var array
     */
    private $_routes = array();
    
    /**
     * @var array
     */
    private $_error_control = array();
    
    /**
     * @var array
     */
    private $_request;

    /**
     * @var string
     */
    private $_uri;
    
    /**
     * @var array | string
     */
    private $_get;
    
    /**
     * @var array | string
     */
    private $_post;
    
    /**
     * @var array | string
     */
    private $_put;
    /**
     * @var array | string
     */
    private $_delete;

    public function add($uri, $callback, $method = NULL) 
    {
        if(!is_callable($callback))
        {
            throw new Exception('two parameter in "add" function accept only callback function!');
        }
       
        $uri = trim($uri, $this->_trim);
        
        $this->_routes[] = array(
            'uri'        => (!empty($uri)) ? $uri : '/',
            'callback'   => $callback,
            'param'      => array(),
            'method'     => isset($method) ? $method : NULL,
        );
    }
    
    public function badRequest($callback)
    {
        if(!is_callable($callback))
        {
            throw new Exception('two parameter in "add" function accept only callback function!');
        }
        
        $this->_error_control = $callback;
    }

    public function listen() 
    {
        try {
            $uri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : '/';

            $this->_uri = $uri;

            foreach ($this->_routes as $_key => $_value)
            {
                if($uri == $_value['uri'])
                {
                    $reflection = new ReflectionFunction($this->_routes[$_key]['callback']);
                    $param = $reflection->getParameters();
                    
                    if(empty($_value['method']) || !$param)
                    {
                        call_user_func($_value['callback']);
                        return false;
                    }
                    else
                    {
                        foreach ($param as $reflecParam)
                        {
                            /* @var $reflecParam ReflectionParameter */
                            $this->_routes[$_key]['param'][$reflecParam->name] = array();
                            $this->_routes[$_key]['param'][$reflecParam->name] = $this->$_value['method']($reflecParam->name);
                        }
                        
                        call_user_func_array(
                            $this->_routes[$_key]['callback'],
                            $this->_routes[$_key]['param']
                        );

                        return false;
                    }
                }
            }

            if(!empty($this->_error_control))
            {
                throw new BadRequestException('Unknown action `' . $this->getUri() . '`', 404);
            }
            else
            {
                self::http_response_code(404);
                echo '
Page Not Found

The page you requested could not be found. It is possible that the address is
incorrect, or that the page no longer exists. Please use a search engine to find
what you are looking for.

error 404               
'.PHP_EOL;
            }
        } catch (BadRequestException $exc) {
            self::http_response_code($exc->getCode());
            call_user_func_array($this->_error_control, array('exc' => $exc));
        }
    }
    
    /**
     * @return string
     */
    public function getUri()
    {
        return $this->_uri;
    }
    
    /**
     * @return array
     */
    public function httpRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'POST') 
        {
            $this->_request['POST'] = $_POST; 
        } elseif($method == 'PUT' || $method == 'DELETE') {
            if (strlen(trim($vars = file_get_contents('php://input'))) === 0)
                return $this->_request = FALSE;
            $put = array(); 
            parse_str($vars, $put);
            $this->_request['PUT'] = $put;
        } elseif($method == 'GET') {
            $this->_request['GET'] = $_GET;
        }
        
        return $this->_request;
    }
    
    /**
     * @param string $name
     * @return array | string
     */
    protected function get($name)
    {
        if($this->_get)
        {
            return $_GET[$name];
        }
        else
        {
            $this->_get = $_GET;
            return $this->get($name);
        }
    }
    
    /**
     * @param string $name
     * @return array | string
     */
    protected function post($name)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'POST')
        {
            if($this->_post)
            {
                return $_POST[$name];
            }
            else
            {
                $this->_post = $_POST;
                return $this->post($name);
            }
        }
        else
        {
            throw new BadRequestException('Content-Type in request is not acceptable.', 415);
        }
    }
    
    /**
     * @param string $name
     * @return boolean | array | string
     */
    protected function put($name)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'PUT')
        {
            if($this->_put)
            {
                return $this->_put[$name];
            }
            else
            {
                if (strlen(trim($vars = file_get_contents('php://input'))) === 0)
                    return FALSE;
                $put = array(); 
                parse_str($vars, $put);
                $this->_put = $put;
                return $this->put($name);        
            }
        }
        else
        {
            throw new BadRequestException('Content-Type in request is not acceptable.', 415);
        }
    }
    
    /**
     * @param string $name
     * @return boolean
     * @throws BadRequestException
     */
    protected function delete($name)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'DELETE')
        {
            if($this->_delete)
            {
                return $this->_delete[$name];
            }
            else
            {
                if (strlen(trim($vars = file_get_contents('php://input'))) === 0)
                    return FALSE;
                $delete = array(); 
                parse_str($vars, $delete);
                $this->_delete = $delete;
                return $this->delete($name);        
            }
        }
        else
        {
            throw new BadRequestException('Content-Type in request is not acceptable.', 415);
        }
    }

    public static function http_response_code($code)
    {
        if (!function_exists('http_response_code')) {
            if ($code !== NULL) {
                switch ($code) {
                    case 100: $text = 'Continue'; break;
                    case 101: $text = 'Switching Protocols'; break;
                    case 200: $text = 'OK'; break;
                    case 201: $text = 'Created'; break;
                    case 202: $text = 'Accepted'; break;
                    case 203: $text = 'Non-Authoritative Information'; break;
                    case 204: $text = 'No Content'; break;
                    case 205: $text = 'Reset Content'; break;
                    case 206: $text = 'Partial Content'; break;
                    case 300: $text = 'Multiple Choices'; break;
                    case 301: $text = 'Moved Permanently'; break;
                    case 302: $text = 'Moved Temporarily'; break;
                    case 303: $text = 'See Other'; break;
                    case 304: $text = 'Not Modified'; break;
                    case 305: $text = 'Use Proxy'; break;
                    case 400: $text = 'Bad Request'; break;
                    case 401: $text = 'Unauthorized'; break;
                    case 402: $text = 'Payment Required'; break;
                    case 403: $text = 'Forbidden'; break;
                    case 404: $text = 'Not Found'; break;
                    case 405: $text = 'Method Not Allowed'; break;
                    case 406: $text = 'Not Acceptable'; break;
                    case 407: $text = 'Proxy Authentication Required'; break;
                    case 408: $text = 'Request Time-out'; break;
                    case 409: $text = 'Conflict'; break;
                    case 410: $text = 'Gone'; break;
                    case 411: $text = 'Length Required'; break;
                    case 412: $text = 'Precondition Failed'; break;
                    case 413: $text = 'Request Entity Too Large'; break;
                    case 414: $text = 'Request-URI Too Large'; break;
                    case 415: $text = 'Unsupported Media Type'; break;
                    case 500: $text = 'Internal Server Error'; break;
                    case 501: $text = 'Not Implemented'; break;
                    case 502: $text = 'Bad Gateway'; break;
                    case 503: $text = 'Service Unavailable'; break;
                    case 504: $text = 'Gateway Time-out'; break;
                    case 505: $text = 'HTTP Version not supported'; break;
                    default:
                        exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
                }
                $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
                header($protocol . ' ' . $code . ' ' . $text);
                $GLOBALS['http_response_code'] = $code;
            } else {
                $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
            }
            return $code;
        }
        else
        {
            http_response_code($code);
        } 
    }
    
    /**
     * Debug listener on local server return true on product return false 
     * 
     * @param string $list
     * @return boolean
     */
    public static function detectDebugMode($list = NULL)
    {
        if(is_null($list))
        {
            if($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
            {
                $list = TRUE;
            }
            else
            {
                $list = FALSE;
            }
        }
        
        return $list;
    }    
}

class BadRequestException extends Exception {
    /** @var int */
    protected $defaultCode = 404;
    
    protected $stackMessage;

    /**
     * @param string | array $message
     * @param integer $code
     * @param \Exception $previous
     */
    public function __construct($message = '', $code = 0, \Exception $previous = NULL)
    {
        if(is_array($message))
        {
            $this->stackMessage = $message;
            $message = '';
        }
        
        if ($code < 200 || $code > 504) {
                $code = $this->defaultCode;
        }

        parent::__construct($message, $code, $previous);
    }
    
    /**
     * @return array
     */
    public function getStackMessage()
    {
        return $this->stackMessage;
    }
}