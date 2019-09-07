<?php
namespace TinyPixel\Modules;

use \add_action;
use Illuminate\Support\Collection;
use eftec\bladeone\BladeOne as Blade;
use TinyPixel\Modules\Modules;
use TinyPixel\Modules\Traits\Dependencies;

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
    use Dependencies;

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
    public static $basePath;

    /**
     * Debugger
     *
     * @var bool
     */
    public static $mode;

    /**
     * Cache path
     *
     * @var string
     */
    public static $cachePath;

    /**
     * Class constructor
     *
     */
    public function __construct(string $basePath)
    {
        add_action('init', function () use ($basePath) {
            $cachePath = wp_upload_dir('block_modules')['path'];

            $blocks = Collection::make();

            self::$cachePath = has_filter('cache_path_blockmodules')
                ? apply_filters('cache_path_blockmodules', $cachePath)
                : $cachePath;

            self::$basePath = has_filter('base_path_blockmodules')
                ? apply_filters('base_path_blockmodules', $basePath)
                : $basePath;

            self::$blocks = has_filter('register_blockmodules')
                ? apply_filters('register_blockmodules', $blocks)
                : $blocks;

            self::$Modules = new Modules(self::$blocks, new Blade(
                self::$basePath,
                self::$cachePath,
                Blade::MODE_AUTO
            ));

            self::$Modules->setUser(\wp_get_current_user());

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
        return plugins_url("{$block['plugin']}") . "/dist/{$block['entry']}";
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
        return ($handle == 'block') ? self::$blockDeps : self::$blockDeps;
    }
}
