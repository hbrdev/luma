<?php

namespace Luma\Http;

use Luma\Std\Container;
use Luma\Std\Environment;
use Luma\Http\Headers;

class Request extends Component
{

    protected $headers;
    protected $scheme;
    protected $host;
    protected $path;
    protected $query;
    protected $method;
    protected $sender;

    const SCHEME_HTTP  = 'http';
    const SCHEME_HTTPS = 'https';

    const METHOD_GET  = 'GET';
    const METHOD_POST = 'POST';

    const SENDER_CLIENT            = 1;
    const SENDER_LOCAL             = 2;
    const SENDER_REWRITTEN_CLIENT  = 3;
    const SENDER_REDIRECTED_CLIENT = 4;

    public function __construct(Environment $env, $params = array())
    {
        $this->configure($env->configurator, $params);
    }
    
    public static function fromPhpEnvironment($extra = array())
    {
        $headers = Headers::fromPhpEnvironment();
        $scheme  = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ?
                   static::SCHEME_HTTPS :
                   static::SCHEME_HTTP;
        $host    = $_SERVER['HTTP_HOST'];
        $path    = $_SERVER['QUERY_STRING'] ?
                   str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']) :
                   $_SERVER['REQUEST_URI'];
        $query   = array();
        if ($_SERVER['QUERY_STRING']) {
            parse_str($_SERVER['QUERY_STRING'], $query);
        }
        $query   = new Container($query);
        $method  = $_SERVER['REQUEST_METHOD'];
        $sender  = static::SENDER_CLIENT;
        
        $params  = array(
            'headers'=>$headers,
            'scheme' =>$scheme,
            'host'   =>$host,
            'path'   =>$path,
            'query'  =>$query,
            'method' =>$method,
            'sender' =>$sender,
        )
        if ($extra) {
            $params  = Container::merge($params, $extra);
        }
        
        return new static($params);
    }
    
    public function getHeaders()
    {
        return $this->headers;
    }
    
    public function setHeaders(Headers $headers)
    {
        $this->headers = $headers;
    }
    
    public function getScheme()
    {
        return $this->scheme;
    }
    
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }
    
    public function getHost()
    {
        return $this->host;
    }
    
    public function setHost($host)
    {
        $this->host = $host;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function setPath($path)
    {
        $this->path = $path;
    }
    
    public function getQuery()
    {
        return $this->query;
    }
    
    public function setQuery($query)
    {
        if (is_array($query)) {
            $query = new Container($query);
        }
        if (!($query instanceof Container)) {
            throw new \Exception(
                'Argument must be an array or instance of Luma\Std\Container class'
            );
        }
        $this->query = $query;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function setMethod($method)
    {
        $this->method = $method;
    }
    
    public function getSender()
    {
        return $this->sender;
    }
    
    public function setSender($sender) {
        $this->sender = $sender;
    }

}