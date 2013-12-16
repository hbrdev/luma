<?php

namespace Luma\Http;

use Luma\Std\Container;

class Headers extends Container
{
    public function __construct(array $headers) 
    {
        parent::__construct($headers);
    }
    
    public static function fromPhpEnvironment()
    {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $header_name = str_replace(' ', '-', ucfirst(str_replace('_', ' ', substr($name, 5))));
                $headers[$header_name] = $value;
            }
        }
        return new static($headers);
    }
}
