<?php

namespace DOOD\Tonic\Registrar\Workflow;

trait CustomHooks
{
    public function enable()
    {
        if ($this->allowed()) {
            $this->run();
        }
    }

    public function hooks()
    {
        // This is empty.
    }

    abstract public function run();
}
