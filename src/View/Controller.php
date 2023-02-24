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
        $this->teach();
        $this->attach($componentsPath);
    }

    protected function teach(): void
    {
        $this->directive(
            'translate',
            fn ($text) => "<?php echo \ICOSE\InspiralReader\Feature\Multilanguage::translate($text); ?>"
        );
        $this->directive(
            'verbose',
            fn ($bool) => "<?php echo boolval($bool) ? 'Yes' : 'No'; ?>"
        );
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
