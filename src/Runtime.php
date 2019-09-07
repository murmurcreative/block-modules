<?php
namespace TinyPixel\Modules;

use \add_action;
use Illuminate\Support\Collection;
use eftec\bladeone\BladeOne as Blade;
use TinyPixel\Modules\Modules;

/**
 * Plugin runtime
 *
 * @since   1.0.0
 * @version 1.0.0
 * @license MIT
 * @author  Kelly Mears <developers@tinypixel.dev>
 */
class Runtime
{
    /**
     * Block Modules
     *
     * @var \TinyPixel\Modules\Modules;
     */
    public static $Modules;

    /**
     * View Engine
     *
     * @var eftec\bladeone\BladeOne
     */
    public static $View;

    /**
     * Collected blocks
     *
     * @var \Illuminate\Support\Collection
     */
    public static $blocks;

    /**
     * Plugins base path
     *
     * @var string
     */
    public static $pluginsPath;

    /**
     * Class constructor
     *
     */
    public function __construct($pluginsPath)
    {
        add_action('init', function () use ($pluginsPath) {
            self::$Modules = new Modules(
                self::$blocks = apply_filters(
                    'register_blockmodules',
                    Collection::make()
                ),
                self::$View = new Blade(
                    self::$pluginsPath = $pluginsPath,
                    wp_upload_dir('block_modules')['path'],
                    Blade::MODE_AUTO
                )
            );

            self::$Modules->registerTemplates();
        });

        add_action('enqueue_block_editor_assets', function () {
            self::enqueue(self::$blocks);
        });
    }

    /**
     * Enqueues blocks
     *
     * @param \Illuminate\Support\Collection $blocks
     * @return void
     */
    private static function enqueue(Collection $blocks) : void
    {
        $blocks->each(function ($block) {
            wp_enqueue_script(
                $block['handle'],
                self::asset($block),
                self::deps($block['handle']),
                ...['', true],
            );
        });
    }

    /**
     * Returns plugin asset path
     *
     * @param  string $block
     * @param  string $path
     */
    private static function asset(array $block) : string
    {
        return plugins_url(
            "{$block['plugin']}/dist/{$block['entry']}",
            self::$pluginsPath
        );
    }

    /**
     * Returns dependencies depending on if
     * script is specified as a block or plugin
     *
     * @param  string $handle
     * @return array
     */
    private static function deps(string $handle) : array
    {
        return ($handle == 'block') ?
            self::$blockDeps :
            self::$extensionDeps;
    }
}
