<?php

namespace DOOD\Tonic\Registrar;

/**
 * The feature set class.
 *
 * This class simply defines a full set of features
 * for the plugin.
 *
 * @since 1.0.0
 * @author Alexandros Raikos <alexandros@dood.gr>
 */
class FeatureSet
{
    /**
     * @var array The array of features.
     * @since 1.0.0
     */
    public array $set;

    /**
     * Instantiate a feature set.
     *
     * @param Feature $features The features.
     * @since 1.0.0
     */
    public function __construct(Feature ...$features)
    {
        $this->set = $features;
    }

    /**
     * Enable all the features in the set.
     *
     * @since 1.0.0
     */
    public function enable(): void
    {
        foreach ($this->set as $feature) {
            $feature->enable();
        }
    }
}
