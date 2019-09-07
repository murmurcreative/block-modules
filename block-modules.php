<?php
/**
 * Plugin Name:     Block Modules
 * Description:     Backbone for modular block building
 * Version:         0.1.0
 * Author:          Kelly Mears, Tiny Pixel
 * Author URI:      https://tinypixel.dev
 * License:         MIT
 * Text Domain:     tinyblocks
 */
namespace TinyPixel\Modules;

require_once __DIR__ . '/vendor/autoload.php';

use TinyPixel\Modules\Runtime;

(new class {
    /**
     * Plugin runtime
     *
     * @param  string $directory
     * @return void
     */
    public function __invoke($pluginsDir) : void
    {
        $runtime = new Runtime(realpath($pluginsDir));
    }
})(__DIR__ . '/..');
