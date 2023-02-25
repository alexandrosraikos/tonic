<?php

namespace DOOD\Tonic\Registrar\Access;

/**
 * The trait for features that are only allowed on the dashboard.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
trait OnDashboardOnly
{
    /**
     * Allows the feature only on the dashboard.
     *
     * @since 1.0.0
     */
    public function allowed(): bool
    {
        return call_user_func('is_admin');
    }
}
