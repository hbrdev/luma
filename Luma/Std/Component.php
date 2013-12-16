<?php

namespace Luma\Std;

class Component extends Object
{
    public function configure(Configurator $configurator, array $extra = array())
    {
        $configuration = $configurator->get(get_class($this));
        
        if ($extra) {
            $configuration->mergeWith($extra);
        }
        
        foreach ($configuration as $property => $value) {
            if (is_array($value)) {
                $this->$property = new Container($value);
            } else {
                $this->$property = $value;
            }
        }
    }
}