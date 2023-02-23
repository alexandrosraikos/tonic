<?php

namespace DOOD\Tonic\Registrar;

use Closure;

class Hook
{
    public string $type;
    public string $name;
    public int $priority;
    public int $arguments;
    public array|string|Closure $completion;

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

    public function register()
    {
        if ($this->type === 'action' && function_exists('add_action')) {
            add_action(
                $this->name,
                $this->completion,
                $this->priority,
                $this->arguments
            );
        }

        if ($this->type === 'filter' && function_exists('add_filter')) {
            add_filter(
                $this->name,
                $this->completion,
                $this->priority,
                $this->arguments
            );
        }
    }
    
    public static function action($name, array|string|Closure $completion, $priority = 10, $arguments = 1)
    {
        return new self(
            'action',
            $name,
            $completion,
            $priority,
            $arguments
        );
    }

    public static function filter($name, array|string|Closure $completion, $priority = 10, $arguments = 1)
    {
        return new self(
            'action',
            $name,
            $completion,
            $priority,
            $arguments
        );
    }

    public static function group(?string $name = null, ?array $properties = null, self ...$hooks): HookSet
    {
        $group = new HookSet($name, $properties, ...$hooks);
        $group->register();
        return $group;
    }
}
