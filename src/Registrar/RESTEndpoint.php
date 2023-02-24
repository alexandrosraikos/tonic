<?php

namespace DOOD\Tonic\Registrar;

use Closure;

class RESTEndpoint extends Hook
{
    /**
     * Register a WP REST API endpoint.
     *
     * @param string $namespace The API namespace in `vendor/v1` format.
     * @param string $route The API route.
     * @param array|string|Closure<array|WP_REST_Response> $responder The responding closure.
     *  This closure receives `array $parameters, WP_REST_Request $request` as parameters.
     * @param string $methods (Optional) The comma separated list of HTTP methods.
     * @param int $priority (Optional) The action priority.
     *
     * @since 1.2.0
     */
    public function __construct(
        string $namespace,
        string $route,
        array|string|Closure $responder,
        string $methods = 'GET',
        int $priority = 10
    ) {
        parent::__construct(
            'action',
            "rest_api_init",
            register_rest_route(
                $namespace,
                $route,
                [
                    'methods' => $methods,
                    'callback' =>
                        fn(WP_REST_Request $request) => $this->handle($request, $responder),
                ]
            ),
            $priority,
            1
        );
    }

    protected function handle(WP_REST_Request $request, Closure $responder): WP_REST_Response
    {
        // Attempt to handle.
        try {
            $response = $responder($request->get_params(), $request);
            if (is_a($response, 'WP_REST_Response')) {
                return $response;
            }
            $response = new WP_REST_Response($response);
            $response->set_status(200);
            return $response;
        } catch (\Exception $e) {
            return (new WP_REST_Response(['error' => $e->getMessage()]))->set_status(500);
        }
    }
}
