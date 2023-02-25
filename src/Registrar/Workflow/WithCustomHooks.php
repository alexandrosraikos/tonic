<?php

namespace DOOD\Tonic\Registrar\Workflow;

/**
 * The trait for features using custom hooks.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
trait WithCustomHooks
{
    /**
     * The conditional method which enables the feature.
     *
     * @since 1.0.0
     */
    public function enable()
    {
        if ($this->allowed()) {
            $this->run();
        }
    }

    /**
     * The empty hooks method overriding the base class.
     *
     * @since 1.0.0
     */
    public function hooks()
    {
        // This is empty.
    }

    /**
     * The method that runs during instantiation.
     *
     * @since 1.0.0
     */
    abstract public function run();
}
