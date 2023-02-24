<?php

namespace DOOD\Tonic\Registrar;

use Closure;

/**
 * The hook set class for bundling hooks together.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class HookSet
{
    /**
     * @var array<Hook> $hooks An array of hooks.
     */
    protected array $hooks;
    
    /**
     * @var ?string $name The name of the hook set.
     */
    public ?string $name;
    
    /**
     * @var ?int $priority The common priority of included hooks.
     */
    public ?int $priority;
    
    
    /**
     * @var ?int $arguments The common number of arguments of included hooks.
     */
    public ?int $arguments;

    protected array|string|Closure|null $completion;

    /**
     * Construct a group of hooks.
     *
     * @param array<Hook> $hooks The array of hooks.
     * @param string $name The group name.
     * @param ?array $properties An optional array of grouped properties.
     *
     * @since 1.0.0
     */
    public function __construct(?string $name, ?array $properties, Hook ...$hooks)
    {
        $this->hooks = $hooks;
        $this->name = $name ?? null;
        
        if (isset($properties)) {
            $this->priority = $properties['priority'] ?? null;
            $this->arguments = $properties['arguments'] ?? null;
            $this->completion = $properties['completion'] ?? null;
        }
    }

    /**
     * Register the included hooks in the WordPress actions list.
     *
     * @since 1.0.0
     */
    public function register()
    {
        foreach ($this->hooks as $hook) {
            if (isset($this->priority)) {
                $hook->priority = $this->priority;
            }
            if (isset($this->arguments)) {
                $hook->arguments = $this->arguments;
            }
            if (isset($this->completion)) {
                $hook->completion = $this->completion;
            }
            $hook->register();
        }
    }
}
