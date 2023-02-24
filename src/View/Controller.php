<?php

namespace DOOD\Tonic\View;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Lexdubyna\Blade\Blade;
use Lexdubyna\Blade\ViewComponent;

class Controller extends Blade
{
    public function __construct(string $viewPaths, string $cachePath, string $componentsPath)
    {
        parent::__construct($viewPaths, $cachePath);
        $this->attach($componentsPath);
    }

    protected function attach(string $path): void
    {
        $components = [];
        foreach (array_keys(ClassMapGenerator::createMap($path)) as $class) {
            $components[$class::$tag] = $class;
        }
        $this->compiler()->components($components);
    }

    protected function partial(ViewComponent $component)
    {
        return $this->compiler()->renderComponent($component);
    }
}
