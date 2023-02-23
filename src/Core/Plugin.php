<?php

namespace DOOD\Tonic\Core;

use DOOD\Tonic\Registrar\FeatureSet;

class Plugin
{
    public string $root;
    public string $identifier;

    public function __construct(string $root, string $identifier)
    {
        $this->root = $root;
        $this->identifier = $identifier;
    }

    public function load(
        FeatureSet $features,
    ) {
        $features->enable();
    }
}
