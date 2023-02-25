<?php

namespace DOOD\Tonic\View;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Lexdubyna\Blade\Blade;
use Lexdubyna\Blade\ViewComponent;

/**
 * The view controller class.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class Controller extends Blade
{
    /**
     * Instantiate the view controller.
     *
     * @param string $viewPaths The path to the views.
     * @param string $cachePath The path to the cache.
     * @param string $componentsPath The path to the components.
     *
     * @since 1.0.0
     */
    public function __construct(string $viewPaths, string $cachePath, string $componentsPath)
    {
        parent::__construct($viewPaths, $cachePath);
        $this->attach($componentsPath);
    }

    /**
     * Attach the views to the compiler.
     *
     * @param string $path The path to the views.
     * @return void
     *
     * @since 1.0.0
     */
    protected function attach(string $path): void
    {
        $components = [];
        foreach (array_keys(ClassMapGenerator::createMap($path)) as $class) {
            $components[$class::$tag] = $class;
        }
        $this->compiler()->components($components);
    }

    /**
     * Render a component.
     *
     * @param ViewComponent $component The component to render.
     *
     * @since 1.0.0
     */
    public function partial(ViewComponent $component)
    {
        return $this->compiler()->renderComponent($component);
    }
}
