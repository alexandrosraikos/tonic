<?php

namespace DOOD\Tonic\Registrar\Workflow;

/**
 * The trait for features using arbitrary methods.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
trait WithArbitraryMethods
{
    /**
     * The conditional method which enables the feature.
     *
     * @since 1.0.0
     */
    public function enable(): void
    {
        if ($this->allowed()) {
            $this->hooks()->register();
            $this->run();
        }
    }

    /**
     * The method that runs during instantiation.
     *
     * @since 1.0.0
     */
    abstract public function run();
}
