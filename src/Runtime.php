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
            self::$basePath  = $this->filter('base_path_blockmodules', $basePath);
            self::$cachePath = $this->filter('cache_path_blockmodules', wp_upload_dir('block_modules')['path']);
            self::$blocks    = $this->filter('register_blockmodules', Collection::make());

            /**
             * \eftec\bladeone\BladeOne
             */
            self::$View = new Blade(
                self::$basePath,
                self::$cachePath,
                self::mode($this->filter('debug_blockmodules', Blade::MODE_AUTO))
            );

            /**
             * \TinyPixel\Modules\Modules
             */
            self::$Modules = new Modules(self::$blocks, self::$View);

            /**
             * Enable @user, @guest, et al.
             */
            if (!$this->filter('disable_user_blockmodules', false)) {
                self::$Modules->setUser(\wp_get_current_user());
            }

            /**
             * Register views with WordPress
             */
            self::$Modules->registerViews();
        });

        /**
         * Enqueue assets
         */
        add_action('enqueue_block_editor_assets', function () {
            self::enqueue(self::$blocks);
        });
    }

    /**
     * Return filter result or default
     *
     * @param  string $filter
     * @param  mixed  $default
     * @return mixed
     */
    public function filter(string $filter, $default)
    {
        if (!has_filter($filter)) {
            return $default;
        }

        return apply_filters($filter, $default);
    }

    /**
     * Set blade engine debug mode
     *
     * @return \eftec\bladeone\BladeOne
     */
    private static function mode($mode)
    {
        return $mode == 'debug' ? Blade::MODE_DEBUG : Blade::MODE_AUTO;
    }

    /**
     * Enqueue block (editor scripts)
     *
     * @param \Illuminate\Support\Collection $blocks
     * @return void
     */
    private static function enqueue(Collection $blocks) : void
    {
        $blocks->each(function ($block) {
            $assetUrl     = plugins_url("{$block['plugin']}/dist/{$block['entry']}");
            $dependencies = isset($block['extension']) ? self::$extensionDeps : self::$blockDeps;

            wp_enqueue_script($block['handle'], $assetUrl, $dependencies, ...['', true]);
        });
    }
}
