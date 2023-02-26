<?php

namespace DOOD\Tonic\Registrar;

use Closure;

/**
 * The hook class for WordPress hooks.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class Hook
{
    /**
     * @var string $type Either `action` or `filter`.
     * @since 1.0.0
     */
    public string $type;
    
    /**
     * @var string $name The name of the action.
     * @since 1.0.0
     */
    public string $name;
    
    /**
     * @var int $priority The hook priority.
     * @since 1.0.0
     */
    public int $priority;
    
    /**
     * @var int $arguments The number of arguments.
     * @since 1.0.0
     */
    public int $arguments;
    
    /**
     * @var array|string|Closure $completion The hook callback.
     * @since 1.0.0
     */
    public array|string|Closure $completion;


    /**
     * Instantiate a hook.
     *
     * @param string $type Either `action` or `filter`.
     * @param string $name The name of the action.
     * @param array|string|Closure $completion The hook callback.
     * @param int $priority The hook priority.
     * @param int $arguments The number of arguments.
     *
     * @since 1.0.0
     */
    protected function __construct(
        string $type,
        string $name,
        array|string|Closure $completion,
        int $priority,
        int $arguments
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->completion = $completion;
        $this->priority = $priority;
        $this->arguments = $arguments;
    }

    /**
     * Register the hook in the WordPress actions list.
     *
     * @since 1.0.0
     */
    public function register()
    {
        if ($this->type === 'action' && function_exists('add_action')) {
            call_user_func(
                'add_action',
                $this->name,
                $this->completion,
                $this->priority,
                $this->arguments
            );
        }

        if ($this->type === 'filter' && function_exists('add_filter')) {
            call_user_func(
                'add_filter',
                $this->name,
                $this->completion,
                $this->priority,
                $this->arguments
            );
        }
    }
    
    /**
     * Instantiate an action hook.
     *
     * @param string $name The name of the action.
     * @param array|string|Closure $completion The hook callback.
     * @param int $priority The hook priority.
     * @param int $arguments The number of arguments.
     *
     * @since 1.0.0
     */
    public static function action($name, array|string|Closure $completion, $priority = 10, $arguments = 1): Hook
    {
        return new self(
            'action',
            $name,
            $completion,
            $priority,
            $arguments
        );
    }


    /**
     * Instantiate a filter hook.
     *
     * @param string $name The name of the action.
     * @param array|string|Closure $completion The hook callback.
     * @param int $priority The hook priority.
     * @param int $arguments The number of arguments.
     *
     * @since 1.0.0
     */
    public static function filter($name, array|string|Closure $completion, $priority = 10, $arguments = 1): Hook
    {
        return new self(
            'action',
            $name,
            $completion,
            $priority,
            $arguments
        );
    }

    /**
     * Instantiate a set of hooks with common properties.
     *
     * @param ?string $name (Optional) The name of the hook set.
     * @param ?array $properties (Optional) An array of common properties.
     *
     * @since 1.0.0
     */
    public static function group(
        string $name,
        array $properties,
        Hook ...$hooks
    ): HookSet {
        $group = new HookSet($name, $properties, ...$hooks);
        $group->register();
        return $group;
    }
}
