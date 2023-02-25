<?php

namespace DOOD\Tonic\Registrar\Access;

/**
 * The trait for features that are only allowed on the public website.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
trait OnWebsiteOnly
{
    /**
     * Allows the feature only on the public website.
     *
     * @since 1.0.0
     */
    public function allowed(): bool
    {
        return !call_user_func('is_admin');
    }
}
