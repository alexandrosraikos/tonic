<?php

namespace DOOD\Tonic\Registrar\Access;

use DOOD\Tonic\Registrar\Workflow\WithoutHooks;

trait OnCronSchedule
{
    use WithoutHooks;

    public function allowed(): bool
    {
        return call_user_func('wp_doing_cron');
    }
}
