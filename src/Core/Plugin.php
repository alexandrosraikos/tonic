<?php

namespace DOOD\Tonic\Core;

use DOOD\Tonic\Registrar\FeatureSet;
use DOOD\Tonic\View\Controller as ViewController;

/**
 * The main plugin class.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class Plugin
{
    /**
     * @var ?self $loaded The loaded plugin.
     * @since 1.0.0
     */
    public static ?self $loaded;

    /**
     * @var Filesystem $filesystem The main filesystem controller.
     * @since 1.0.0
     */
    public Filesystem $filesystem;

    /**
     * @var ViewController $view The main view controller.
     * @since 1.0.0
     */
    public ViewController $view;

    /**
     * @var FeatureSet $features The main feature set of the plugin.
     * @since 1.0.0
     */
    public FeatureSet $features;

    /**
     * Instantiate a new plugin.
     *
     * @param string $directory The full path to the plugin.
     * @param bool $load Whether to load the plugin automatically.
     *
     * @since 1.0.0
     */
    public function __construct(string $directory, bool $load = true)
    {
        // If this file is called directly, abort.
        if (!defined('WPINC')) {
            die;
        }

        // Instantiate plugin properties.
        $this->filesystem = new Filesystem($directory);
        $this->features = $this->features();
        $this->view = new ViewController(
            $this->filesystem->path('/resources/views'),
            $this->filesystem->path('/public/cache'),
            $this->filesystem->path('/plugin/View/Component')
        );

        // Keep a single plugin instance reference in memory.
        self::$loaded = $this;

        // Load the plugin.
        if ($load) {
            $this->load();
        }
    }

    /**
     * Parse the Feature classes from the relevant folder.
     *
     * @return FeatureSet The parsed set of features.
     * @since 1.0.0
     */
    protected function features(): FeatureSet
    {
        return new FeatureSet(
            ...array_map(
                fn($feature) => new $feature(),
                $this->filesystem->classes('/plugin/Feature')
            )
        );
    }

    /**
     * Enable the plugin's features.
     *
     * @since 1.0.0
     */
    public function load(): void
    {
        $this->features->enable();
    }

    /**
     * Retrieve the last plugin instance.
     *
     * @since 1.0.0
     */
    public static function this(): self|bool
    {
        return self::$loaded ?? false;
    }
}
