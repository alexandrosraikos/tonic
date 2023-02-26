<?php

namespace DOOD\Tonic\Utilities;

use DOOD\Tonic\Core\Plugin;
use DOOD\Tonic\Registrar\Feature;
use DOOD\Tonic\Registrar\Hook;

/**
 * The browser scripts feature.
 *
 * @since 1.2.0
 */
class BrowserScripts extends Feature
{
    /**
     * The feature hooks.
     *
     * @since 1.2.0
     */
    public function hooks()
    {
        return Hook::group(
            'shortcodes',
            [],
            Hook::action(
                'wp_enqueue_scripts',
                [$this, 'styles']
            ),
            Hook::action(
                'wp_enqueue_scripts',
                [$this, 'scripts']
            )
        );
    }


    /**
     * Register the plugin's scripts.
     *
     * @since 1.2.0
     */
    public function scripts(): void
    {
        // Enqueue the WP API.
        call_user_func(
            'wp_enqueue_script',
            'wp-api'
        );
        
        // Enqueue the plugin's scripts.
        call_user_func(
            'wp_enqueue_script',
            Plugin::this()->identifier(),
            Plugin::this()->asset('js/'.Plugin::this()->identifier().'.js'),
            ['jquery', 'wp-api'],
            Plugin::this()->version,
            true
        );
        // Localize the plugin's scripts.
        call_user_func(
            'wp_localize_script',
            'TonicProperties',
            Plugin::this()->identifier(),
            [
                'ajaxURL' => call_user_func('admin_url', 'admin-ajax.php')
            ]
        );
    }

    /**
     * Register the plugin's styles.
     *
     * @since 1.2.0
     */
    public function styles(): void
    {
        call_user_func(
            'wp_enqueue_style',
            Plugin::this()->identifier(),
            Plugin::this()->asset('css/'.Plugin::this()->identifier().'.css'),
            [],
            Plugin::this()->version
        );
    }
}
