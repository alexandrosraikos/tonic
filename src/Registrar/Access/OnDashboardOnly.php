<?php

namespace DOOD\Tonic\Registrar\Access;

trait OnDashboardOnly
{
    public function allowed(): bool
    {
        return call_user_func('is_admin');
    }
}
