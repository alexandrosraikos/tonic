<?php

namespace DOOD\Tonic\Registrar;

/**
 * The feature class for plugin features.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
abstract class Feature
{
    /**
     * Check permissions and register the fetaure's hooks.
     *
     * @since 1.0.0
     */
    public function enable(): void
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
     *
     * @return bool Whether the feature is allowed.
     * @since 1.0.0
     */
    public function allowed(): bool
    {
        return !call_user_func('wp_doing_cron');
    }
}
