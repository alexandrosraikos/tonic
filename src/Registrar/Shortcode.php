<?php

namespace DOOD\Tonic\Registrar;

use Closure;
use DOOD\Tonic\Registrar\AJAXEndpoint;
use DOOD\Tonic\View\ViewController;
use Error;
use ReflectionObject;
use ReflectionProperty;

abstract class Shortcode
{
    protected static string $tag;
    protected static array $attributes = [];
    protected static array $required = [];

    /**
     * Publish the desired shortcode with error handling capabilities.
     *
     * @since 0.0.3
     * @version 2.0
     */
    public static function add(ViewController $view): void
    {
        if (!is_admin()) {
            // Register the shortcode.
            add_shortcode(
                str_replace('-', '_', static::$tag),
                fn ($attributes) => static::register($view, $attributes)
            );
        }
    }

    protected static function ajax(
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
     * Safely extract the shortcode attributes from
     * the attribute list.
     *
     * @since 0.0.1
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

        return shortcode_atts(static::$attributes, $providedAttributes);
    }
    
    /**
     * Handle a shortcode print.
     *
     * This function enqueues all relevant stylesheets and
     * handles all shortcode exceptions by printing
     * notices properly.
     *
     * @param array $atts The shortcode attributes.
     * @param string $tag The shortcode identifier.
     * @param callable $content The content printing function.
     *
     * @since 0.0.1
     */
    protected static function register(ViewController $view, array|string $attributes): string
    {
        $instance = new static(static::check($attributes));
        $properties = (new ReflectionObject($instance))->getProperties(ReflectionProperty::IS_PUBLIC);
        $data = [];
        foreach ($properties as $property) {
            if (isset($instance->{$property->getName()})) {
                $data[$property->getName()] = $instance->{$property->getName()};
            }
        }
        return static::print($view, $data);
    }

    protected static function print(ViewController $view, array $data): string
    {
        return $view->render(
            static::$tag,
            $data
        );
    }
}
