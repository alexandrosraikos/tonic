<?php

namespace DOOD\Tonic\View;

use DOOD\Tonic\Core\Plugin;
use Lexdubyna\Blade\ViewComponent;

/**
 *  The view component class.
 *
 *  @since 1.0.0
 *  @author Alexandros Raikos <alexandros@dood.gr>
 */
class Component extends ViewComponent
{
    /**
     * Render the component in a standalone context.
     *
     * @since 1.0.0
     */
    public function standalone(): string
    {
        return Plugin::this()->view->partial($this);
    }
}
