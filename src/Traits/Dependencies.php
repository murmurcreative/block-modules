<?php
namespace TinyPixel\Modules\Traits;

trait Dependencies
{
    /**
     * Block base dependencies
     *
     * @var array
     */
    public static $blockDeps = ['wp-editor', 'wp-element', 'wp-blocks'];

    /**
     * Editor extension base dependencies
     *
     * @var array
     */
    public static $extensionDeps = ['wp-editor', 'wp-element', 'wp-plugins', 'wp-dom-ready', 'wp-edit-post'];
}
