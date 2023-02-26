<?php

namespace DOOD\Tonic\Core;

use DOOD\Tonic\Registrar\FeatureSet;
use DOOD\Tonic\Utilities\BrowserScripts;
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
    protected string $identifier;

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
     * @version 1.2.0 Added $identifier and $version.
     * @since 1.0.0
     */
    public function __construct(
        string $identifier,
        string $version,
        string $directory,
        array $options = [
            'multilanguage' => true,
            'browser_scripts' => true,
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

        // Enable browser scripts.
        if ($options['browser_scripts']) {
            $this->features->set[] = new BrowserScripts();
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
     * Enable the plugin's features.
     *
     * @since 1.0.0
     */
    public function load(): void
    {
        $this->features->enable();
    }

    /**
     * Retrieve the URI to the given path.
     *
     * @param string $path The relative path to the file.
     * @return string The full URI to the file.
     *
     * @since 1.2.0
     */
    public function asset(string $path): string
    {
        return plugins_url(
            end(explode('/', $path)),
            $this->path().'/public/'.$path,
        );
    }

    /**
     * Retrieve the full path to the plugin folder.
     *
     * @return string The full path to the plugin folder.
     *
     * @since 1.2.0
     */
    public function path(): string
    {
        return $this->filesystem->root;
    }

    /**
     * Retrieve the plugin identifier.
     *
     * @param bool $snake Whether to return the identifier in snake case.
     * @return string The plugin identifier.
     *
     * @since 1.2.0
     */
    public function identifier(bool $snake = false): string
    {
        return $snake
            ? str_replace('-', '_', $this->identifier)
            : $this->identifier;
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
