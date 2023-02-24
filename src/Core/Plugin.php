<?php

namespace DOOD\Tonic\Core;

use DOOD\Tonic\Registrar\FeatureSet;
use DOOD\Tonic\View\Controller as ViewController;

class Plugin
{
    public static ?self $loaded;
    public Filesystem $filesystem;
    public ViewController $view;
    public FeatureSet $features;

    public function __construct(string $directory, bool $load = true)
    {
        // If this file is called directly, abort.
        if (!defined('WPINC')) {
            die;
        }

        $this->filesystem = new Filesystem($directory);
        $this->features = $this->features();
        $this->view = new ViewController(
            $this->filesystem->path('/resources/views'),
            $this->filesystem->path('/public/cache'),
            $this->filesystem->path('/plugin/View/Component')
        );
        self::$loaded = $this;

        if ($load) {
            $this->load();
        }
    }

    protected function features(): FeatureSet
    {
        return new FeatureSet(
            ...array_map(
                fn($feature) => new $feature(),
                $this->filesystem->classes('/plugin/Feature')
            )
        );
    }

    public function load()
    {
        $this->features->enable();
    }

    public static function this(): self|false
    {
        return self::$loaded ?? false;
    }
}
