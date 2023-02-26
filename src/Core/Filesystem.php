<?php

namespace DOOD\Tonic\Core;

use Composer\ClassMapGenerator\ClassMapGenerator;

/**
 * The filesystem handler class.
 *
 * Used to abstract all the filesystem related functionality.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class Filesystem
{
    /**
     * @var string The root path of the plugin.
     * @since 1.0.0
     */
    public string $root;


    /**
     * Instantiate a filesystem handler.
     *
     * @param string $root The full path to the root directory.
     *
     * @since 1.0.0
     */
    public function __construct(string $root)
    {
        $this->root = $root;
    }

    /**
     * Retrieve an array of class names.
     *
     * @param string $relative The relative directory path (with leading slash).
     * @return array An array of fully qualified class names.
     *
     * @since 1.0.0
     */
    public function classes(string $directory): array
    {
        return array_keys(ClassMapGenerator::createMap($this->root.$directory));
    }

    /**
     * Retrieve the full system path from a relative path.
     *
     * @param string $relative A relative path to a file
     *  or directory within the plugin (without leading slash).
     *
     * @since 1.0.0
     */
    public function path(string $relative): string
    {
        return $this->root.'/'.$relative;
    }
}
