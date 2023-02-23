<?php

namespace DOOD\Tonic\Registrar;

use Closure;

class HookSet
{
    /**
     * @var array<Hook> An array of hooks.
     */
    protected array $hooks;

    public ?string $name;

    public ?int $priority;

    public ?int $arguments;

    protected array|string|Closure|null $completion;

    /**
     * Construct a group of hooks.
     *
     * @param array<Hook> $hooks The array of hooks.
     * @param string $name The group name.
     * @param ?array $properties An optional array of grouped properties.
     *
     * @since 0.0.3
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
