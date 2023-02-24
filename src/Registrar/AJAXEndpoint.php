<?php

namespace DOOD\Tonic\Registrar;

use Closure;

/**
 * The controller class for handling client AJAX calls.
 *
 * @since 1.1.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class AJAXEndpoint extends Hook
{
    /**
     * Instantiate an AJAX endpoint.
     * @param string $pluginID The plugin ID.
     * @param string $actionID The AJAX action ID.
     * @param array|string|Closure $responder The responding callback.
     * @param int $priority = 10 The hook priority.
     * @param int $arguments = 1  The number of arguments.
     * @param bool $private = false Whether to limit to administrators.
     *
     * @since 1.1.0
     */
    public function __construct(
        string $pluginID,
        string $actionID,
        array|string|Closure $responder,
        int $priority = 10,
        int $arguments = 1,
        bool $private = false
    ) {
        $private = $private ? '' : '_nopriv';
        parent::__construct(
            'action',
            "wp_ajax{$private}_{$pluginID}_{$actionID}",
            fn() => $this->handle($responder),
            $priority,
            $arguments
        );
    }

    /**
     * Validate specific AJAX POST parameters.
     *
     * @return void
     * @since 1.1.0
     */
    protected static function validate(): void
    {
        // Check for mandatory request fields.
        if (!isset($_POST['action']) || !isset($_POST['nonce'])) {
            http_response_code(400);
            die('The required fields were not specified.');
        }

        // Verify the action related nonce.
        $action = call_user_func('sanitize_key', $_POST['action']);
        $nonce = call_user_func(
            'sanitize_text_field',
            call_user_func('wp_unslash', $_POST['nonce'])
        );
        if (!call_user_func('wp_verify_nonce', $nonce, $action)) {
            http_response_code(403);
            die('Unverified request for action: ' . call_user_func('esc_attr', $action));
        }
    }

    /**
     * Handle an AJAX request.
     *
     * @param callable $completion The completion.
     * @return void
     *
     * @since 1.1.0
     */
    protected function handle(callable $completion): void
    {
        /** @var callable $report Respond using `die()`. */
        $report = function (mixed $response, int $http_status = 0): never {
            http_response_code($http_status);
            if (!empty($response)) {
                die($response);
            }
            die();
        };

        // Attempt to handle.
        try {
            self::validate();
            $data = $completion(
                array_filter(
                    $_POST,
                    function ($key) {
                        return ('action' !== $key && 'nonce' !== $key);
                    },
                    ARRAY_FILTER_USE_KEY
                )
            );
            $report(!empty($data) ? call_user_func('wp_json_encode', $data) : null, 200);
        } catch (\Exception $e) {
            $report($e->getMessage(), 500);
        }
    }
}
