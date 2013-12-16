<?php

namespace Luma\Std;

class Configurator
{
    protected $configuration;

    public function __construct($configuration)
    {
        if (is_array($configuration)) {
            $this->configuration = new Container($configuration);
        } elseif (is_file($configuration)) {
            $this->configuration = new Container(include $configuration);
        }
    }

    public function configure($component)
    {
        $component->configure($this);
    }
}