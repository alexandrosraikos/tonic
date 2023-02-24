<?php

namespace DOOD\Tonic\Registrar\Workflow;

trait WithArbitraryMethods
{
    public function enable()
    {
        if ($this->allowed()) {
            $this->hooks()->register();
            $this->run();
        }
    }

    abstract public function run();
}
