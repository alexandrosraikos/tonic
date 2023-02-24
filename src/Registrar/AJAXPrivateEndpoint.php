<?php

namespace DOOD\Tonic\Registrar;

use Closure;

/**
 * The controller class for handling dashboard AJAX calls.
 *
 * @since 1.1.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class AJAXPrivateEndpoint extends AJAXEndpoint
{

    /**
     * Instantiate a private AJAX endpoint for the dashboard.
     *
     * @param string $pluginID The plugin ID.
     * @param string $actionID The AJAX action ID.
     * @param array|string|Closure $responder The responding callback.
     * @param int $priority = 10 The hook priority.
     * @param int $arguments = 1  The number of arguments.
     *
     * @since 1.1.0
     */
    public function __construct(
        string $pluginID,
        string $actionID,
        array|string|Closure $responder,
        int $priority = 10,
        int $arguments = 1
    ) {
        parent::__construct(
            'action',
            "wp_ajax_{$pluginID}_{$actionID}",
            fn() => $this->handle($responder),
            $priority,
            $arguments,
            true
        );
    }
}
