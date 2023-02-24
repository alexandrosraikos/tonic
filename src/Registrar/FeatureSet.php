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
    protected array $features;

    /**
     * Instantiate a feature set.
     *
     * @param Feature $features The features.
     * @since 1.0.0
     */
    public function __construct(Feature ...$features)
    {
        $this->features = $features;
    }

    /**
     * Enable all the features in the set.
     *
     * @since 1.0.0
     */
    public function enable(): void
    {
        foreach ($this->features as $feature) {
            $feature->enable();
        }
    }
}
