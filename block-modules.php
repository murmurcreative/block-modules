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
     */
    public function __invoke(string $directory)
    {
        $runtime = new Runtime($directory);
    }
})(__DIR__ . '/..');
