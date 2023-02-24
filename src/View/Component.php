<?php

namespace DOOD\Tonic\View;

use DOOD\Tonic\Core\Plugin;
use Lexdubyna\Blade\ViewComponent;

class Component extends ViewComponent
{
    public function standalone(): string
    {
        return Plugin::this()->view->partial($this);
    }
}
