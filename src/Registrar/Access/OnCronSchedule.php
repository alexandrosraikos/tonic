<?php

namespace DOOD\Tonic\Registrar\Access;

use DOOD\Tonic\Registrar\Workflow\WithoutHooks;

/**
 * The trait for features that are only allowed on cron schedules.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
trait OnCronSchedule
{
    use WithoutHooks;

    /**
     * Allows the feature only on cron schedules.
     *
     * @since 1.0.0
     */
    public function allowed(): bool
    {
        return call_user_func('wp_doing_cron');
    }
}
