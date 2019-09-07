<?php
namespace TinyPixel\Modules;

use \add_action;
use \register_block_type;
use Illuminate\Support\Collection;
use eftec\bladeone\BladeOne as Blade;

/**
 * Block Modules
 *
 * @since   1.0.0
 * @version 1.0.0
 * @license MIT
 * @author  Kelly Mears <developers@tinypixel.dev>
 */
class Modules
{
    /**
     * Collected blocks
     *
     * @var \Illuminate\Support\Collection
     */
    public static $blocks;

    /**
     * View Engine
     *
     * @var eftec\bladeone\BladeOne
     */
    public static $View;

    /**
     * Constructor
     *
     * @param Illuminate\Support\Collection
     * @param eftec\bladeone\BladeOne
     */
    public function __construct(Collection $blocks, Blade $view)
    {
        self::$blocks = $blocks;
        self::$View   = $view;
    }

    /**
     * Register block templates
     *
     * @return void
     */
    public function registerTemplates() : void
    {
        self::$blocks->each(function ($block) {
            register_block_type($block['handle'], [
                'editor_script' => $block['entry'],
                'render_callback' => [$this, 'render', $block],
            ]);
        });
    }

    /**
     * Renders Template
     *
     * @param  array  $attributes
     * @param  string $content
     * @param  array  $options
     *
     * @return void
     */
    public function render(array $attr, string $content, array $options)
    {
        self::$View->run($this->template($options['name']), [
            'attr'    => (object) $attr,
            'content' => $content,
        ]);
    }

    /**
     * Returns template path
     *
     * @param  string $blockName
     * @return string
     */
    public function template(string $blockName)
    {
        $block = self::$blocks->where('handle', $blockName);

        $fileName = "{$block->pluck('blade')->first()}.blade.php";
        $pluginName = $block->pluck('plugin')->first();

        return "{$pluginName}/src/{$fileName}";
    }
}
