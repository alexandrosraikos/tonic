<?php

namespace DOOD\Tonic\Registrar;

use Closure;
use Error;

/**
 * The WordPress REST endpoint hook class.
 *
 * @since 1.1.2
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class RESTEndpoint extends Hook
{
    /**
     * @var string $namespace The API namespace.
     *
     * @since 1.1.2
     */
    public string $namespace;
    
    /**
     * @var string $route The API route.
     *
     * @since 1.1.2
     */
    public string $route;
    
    /**
     * @var array|string|Closure $responder The responding callback.
     *
     * @since 1.1.2
     */
    public array|string|Closure $responder;
    
    /**
     * @var string $methods The comma separated string of HTTP methods.
     *
     * @since 1.1.2
     */
    public string $methods;

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
        $this->namespace = $namespace;
        $this->route = $route;
        $this->responder = $responder;
        $this->methods = $methods;

        parent::__construct(
            'action',
            'rest_api_init',
            [$this, 'publish'],
            $priority,
            1
        );
    }

    /**
     * Publish the REST endpoint.
     *
     * @return void
     * @since 1.1.2
     */
    public function publish(): void
    {
        $registered = call_user_func(
            'register_rest_route',
            $this->namespace,
            $this->route,
            [
                'methods' => $this->methods,
                'callback' => [$this, 'handle'],
            ]
        );

        if (!$registered) {
            throw new Error("The REST endpoint couldn't be registered.");
        }
    }

    /**
     * Handle the responding callback.
     *
     * @return WP_REST_Response The response
     * @since 1.1.2
     */
    public function handle(\WP_REST_Request $request): \WP_REST_Response
    {
        // Attempt to handle.
        try {
            $response = call_user_func($this->responder, $request);
            if (is_a($response, 'WP_REST_Response')) {
                return $response;
            }
            $response = new \WP_REST_Response($response);
            $response->set_status(200);
            return $response;
        } catch (\Exception $e) {
            $response = new \WP_REST_Response($e->getMessage());
            $response->set_status(500);
            return $response;
        }
    }
}
