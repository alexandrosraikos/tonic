<?php

namespace DOOD\Tonic\Core;

use DOOD\Tonic\Registrar\FeatureSet;
use DOOD\Tonic\View\Controller as ViewController;

class Plugin
{
    public static self $loaded;
    public Filesystem $filesystem;
    public ViewController $view;
    public array $features;

    public function __construct()
    {
        // If this file is called directly, abort.
        if (!defined('WPINC')) {
            die;
        }

        $this->filesystem = new Filesystem();
        $this->features = $this->features();
        $this->view = new ViewController(
            $this->filesystem->path('/resources/views'),
            $this->filesystem->path('/public/cache'),
            $this->filesystem->path('/plugin/View/Component')
        );

        $this->features->enable();

        self::$loaded = $this;
    }

    protected function features(): FeatureSet
    {
        return new FeatureSet(
            array_map(
                fn($feature) => new $feature(),
                $this->filesystem->classes('/plugin/Feature')
            )
        );
    }

    public function load(
        FeatureSet $features,
    ) {
        $features->enable();
    }

    public static function this(): self
    {
        if (self::$loaded === null) {
            self::$loaded = new self();
        }
        return self::$loaded;
    }
}
