<?php

namespace Luma\Std;

class Environment
{
    protected $env;
    protected $configurator;

    const ENV_PRODUCTION  = 'Production';
    const ENV_DEVELOPMENT = 'Development';
    const ENV_TEST        = 'Test';
    
    public function __construct($env, Configurator $configurator)
    {
        $this->env = $env;
        $this->configurator = $configurator;
    }
}