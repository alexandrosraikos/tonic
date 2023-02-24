<?php

namespace DOOD\Tonic\Registrar;

class FeatureSet
{
    protected array $features;

    public function __construct(Feature|array ...$features)
    {
        $this->features = $features;
    }

    public function enable()
    {
        foreach ($this->features as $feature) {
            $feature->enable();
        }
    }
}
