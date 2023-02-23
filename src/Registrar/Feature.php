<?php

namespace DOOD\Tonic\Registrar;

abstract class Feature
{
    public function enable()
    {
        if ($this->allowed()) {
            $this->hooks()->register();
        }
    }

    /**
     * Define the hooks of this feature.
     *
     * @return Hook|HookSet
     * @since 1.0.0
     */
    abstract public function hooks();

    /**
     * Check whether this feature is allowed.
     *
     * This check is used to avoid loading unnecessary
     * features on WordPress. It is allowed by default
     * but it can be overriden, either with a custom
     * function or by using one of the \DOOD\Support\Acc
     */
    public function allowed(): bool
    {
        return true;
    }
}
