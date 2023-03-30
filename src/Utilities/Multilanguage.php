<?php

namespace DOOD\Tonic\Utilities;

use DOOD\Tonic\Core\Plugin;
use DOOD\Tonic\Registrar\Feature;
use DOOD\Tonic\Registrar\Hook;
use DOOD\Tonic\Registrar\Workflow\WithArbitraryMethods;

/**
 * The multilanguage feature.
 *
 * @since 1.0.0
 */
class Multilanguage extends Feature
{
    use WithArbitraryMethods;

    /**
     * The feature hooks.
     *
     * @since 1.2.0
     */
    public function hooks()
    {
        return Hook::action('plugins_loaded', fn() => call_user_func(
            'load_plugin_textdomain',
            Plugin::this()->identifier(),
            false,
            Plugin::this()->identifier().'/languages'
        ));
    }

    /**
     * The feature workflow.
     *
     * @since 1.2.0
     */
    public function run(): void
    {
        Plugin::this()->view->directive(
            'translate',
            fn ($text) => "<?php echo " . Multilanguage::class . "::translate($text); ?>"
        );
    }

    /**
     * Translate a string.
     *
     * @param string $text The text to translate.
     * @return string
     *
     * @since 1.2.0
     */
    public static function translate(string $text): string
    {
        return call_user_func('__', $text, Plugin::this()->identifier());
    }

    /**
     * Print a translated string.
     *
     * @param string $text The text to translate.
     * @return void
     *
     * @since 1.2.0
     */
    public static function print(string $text): void
    {
        print self::translate($text);
    }

    /**
     * Parse a .mo file.
     *
     * @param string $lang The language to parse (ISO639-1).
     * @return array
     *
     * @since 1.2.0
     */
    public static function parse(string $lang): array
    {
        // Parse the .mo file
        $mo = new \MO();
        $file = Plugin::this()->path().'/languages/'.
            Plugin::this()->identifier().'-'.substr($lang, 0, 2).'.mo';

        if (!file_exists($file)) {
            return [];
        }
        
        if ($mo->import_from_file(
            Plugin::this()->path().'/languages/'.
            Plugin::this()->identifier().'-'.substr($lang, 0, 2).'.mo'
        )) {
            return $mo->entries;
        } else {
            throw new \Error('Could not parse the .mo file.');
        }
    }
}
