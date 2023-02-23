<?php

namespace DOOD\Tonic\Registrar;

use Closure;

class AJAXPrivateEndpoint extends AJAXEndpoint
{
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
