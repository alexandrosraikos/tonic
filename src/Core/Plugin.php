<?php

namespace DOOD\Tonic\Core;

use DOOD\Tonic\Registrar\FeatureSet;
use DOOD\Tonic\Utilities\Multilanguage;
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
    protected static ?self $loaded;

    /**
     * @var string $identifier The plugin identifier.
     * @since 1.2.0
     */
    public string $identifier;

    /**
     * @var string $version The plugin version.
     * @since 1.2.0
     */
    public string $version;

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
     * @param string $identifier The plugin identifier.
     * @param string $version The plugin version.
     * @param string $directory The full path to the plugin.
     * @param array<string,bool> $options The plugin options.
     * @param bool $load Whether to load the plugin automatically.
     *
     * @since 1.0.0
     */
    public function __construct(
        string $identifier,
        string $version,
        string $directory,
        array $options = [
            'multilanguage' => true
        ],
        bool $load = true
    ) {
        // If this file is called directly, abort.
        if (!defined('WPINC')) {
            die;
        }

        // Instantiate plugin properties.
        $this->identifier = $identifier;
        $this->version = $version;
        $this->filesystem = new Filesystem($directory);
        $this->features = $this->features();
        $this->view = new ViewController(
            $this->filesystem->path('/resources/views'),
            $this->filesystem->path('/public/cache'),
            $this->filesystem->path('/plugin/View/Component')
        );

        // Enable multilanguage support.
        if ($options['multilanguage']) {
            $this->features->set[] = new Multilanguage();
        }

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
     * Register the plugin's scripts.
     *
     * @since 1.2.0
     */
    protected function scripts(): void
    {
        $scripts = $this->filesystem->files('/public/js', '.js');
        foreach ($scripts as $script) {
            call_user_func(
                'wp_enqueue_script',
                $this->identifier,
                $script,
                ['jquery'],
                $this->version,
                true
            );
        }
    }

    /**
     * Register the plugin's styles.
     *
     * @since 1.2.0
     */
    protected function styles(): void
    {
        $styles = $this->filesystem->files('/public/css', '.css');
        foreach ($styles as $style) {
            call_user_func(
                'wp_enqueue_style',
                $this->identifier,
                $style,
                [],
                $this->version,
                'all'
            );
        }
    }

    /**
     * Enable the plugin's features.
     *
     * @since 1.0.0
     */
    public function load(): void
    {
        $this->features->enable();
        $this->scripts();
        $this->styles();
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
