<?php

/**
 * Plugin Name: Example Block
 * Description: Example implementation of tiny-blocks
 */


add_filter('register_blockmodules', function ($blocks) {
    $blocks->push([
      'plugin' => 'example-block',
      'handle' => 'example/block',
      'entry'  => 'index.js',
      'blade'  => 'blade/render',
    ]);

    return $blocks;
});
