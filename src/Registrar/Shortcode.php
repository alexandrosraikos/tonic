<?php

namespace DOOD\Tonic\Registrar;

use Closure;
use DOOD\Tonic\Core\Plugin;
use DOOD\Tonic\Registrar\AJAXEndpoint;
use Error;
use ReflectionObject;
use ReflectionProperty;

/**
 * The Shortcode class is a base class for all shortcodes.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
abstract class Shortcode
{
    /**
     * The shortcode tag.
     *
     * @var string
     */
    protected static string $tag;

    /**
     * The shortcode attributes.
     *
     * @var array
     * @since 1.0.0
     */
    protected static array $attributes = [];

    /**
     * The shortcode required attributes.
     *
     * @var array
     * @since 1.0.0
     */
    protected static array $required = [];

    /**
     * The shortcode constructor.
     *
     * @param array $attributes The shortcode attributes.
     *
     * @since 1.0.0
     */
    abstract public function __construct(array $attributes);
    
    /**
     * Publish the desired shortcode with error handling capabilities.
     *
     * @return void
     *
     * @since 1.0.0
     * @version 2.0
     */
    public static function add(): void
    {
        if (!call_user_func('is_admin')) {
            // Register the shortcode.
            call_user_func(
                'add_shortcode',
                str_replace('-', '_', static::$tag),
                fn ($attributes) => static::register($attributes)
            );
        }
    }

    /**
     * Register a new AJAX endpoint.
     *
     * @param string $pluginID The plugin ID.
     * @param string $action The AJAX action.
     * @param array|string|Closure $responder The AJAX responder.
     * @return void
     *
     * @since 1.0.0
     */
    protected function ajax(
        string $pluginID,
        string $action,
        array|string|Closure $responder
    ) {
        (new AJAXEndpoint(
            $pluginID,
            $action,
            $responder
        ))->register();
    }

    /**
     * Register a new REST endpoint.
     *
     * @param string $namespace The REST namespace.
     * @param string $route The REST route.
     * @param array|string|Closure $responder The REST responder.
     * @param string $methods The REST methods.
     * @param int $priority The REST priority.
     * @return void
     *
     * @since 1.0.0
     */
    protected function endpoint(
        string $namespace,
        string $route,
        array|string|Closure $responder,
        string $methods = 'GET',
        int $priority = 10
    ) {
        (new RESTEndpoint(
            $namespace,
            $route,
            $responder,
            $methods,
            $priority
        ))->register();
    }

    /**
     * Safely extract the shortcode attributes from
     * the attribute list.
     *
     * @param array|string $providedAttributes The provided attributes.
     * @return array The extracted attributes.
     *
     * @since 1.0.0
     */
    protected static function check(array|string $providedAttributes): array
    {
        if (is_string($providedAttributes)) {
            throw new Error('There was a problem configuring the shortcode.');
        }

        foreach (static::$required as $requiredAttribute) {
            if (!in_array($requiredAttribute, array_keys($providedAttributes), true)) {
                throw new Error(
                    sprintf(
                        'Please insert the %s attribute into the shortcode.',
                        $requiredAttribute
                    )
                );
            }
        }

        return call_user_func('shortcode_atts', static::$attributes, $providedAttributes);
    }
    
    /**
     * Handle a shortcode print.
     *
     * This function enqueues all relevant stylesheets and
     * handles all shortcode exceptions by printing
     * notices properly.
     *
     * @param callable $content The content printing function.
     * @return string The shortcode print.
     *
     * @since 1.0.0
     */
    protected static function register(array|string $attributes): string
    {
        try {
            $instance = new static(static::check($attributes));
            $properties = (new ReflectionObject($instance))->getProperties(ReflectionProperty::IS_PUBLIC);
            $data = [];
            foreach ($properties as $property) {
                if (isset($instance->{$property->getName()})) {
                    $data[$property->getName()] = $instance->{$property->getName()};
                }
            }
            return static::print($data);
        } catch (Error $error) {
            // TODO @alexandrosraikos: Add a debuggable exception support.
            // TODO @alexandrosraikos: Make a notice view component class.
            return $error->getMessage();
        }
    }

    /**
     * Print the shortcode.
     *
     * @param array $data The data to be printed.
     * @return string The shortcode print.
     *
     * @since 1.0.0
     */
    protected static function print(array $data): string
    {
        return Plugin::this()->view->render(
            static::$tag,
            $data
        );
    }
}
