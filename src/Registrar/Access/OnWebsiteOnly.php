<?php

namespace DOOD\Tonic\Registrar\Access;

trait OnWebsiteOnly
{
    public function allowed(): bool
    {
        return !call_user_func('is_admin');
    }
}
