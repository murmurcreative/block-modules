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
    public function registerViews() : void
    {
        self::$blocks->each(function ($block) {
            if (is_null($block['blade'])) {
                return;
            }

            register_block_type($block['handle'], [
                'editor_script' => $block['entry'],
                'render_callback' => function ($attr, $content) use ($block) {
                    $data = [
                        'attr' => (object) $attr,
                        'content' => $content,
                    ];

                    return self::$View->run(
                        $this->view($block['handle']),
                        $data
                    );
                },
            ]);
        });
    }

    /**
     * Returns view
     *
     * @param  string $blockName
     * @return string
     */
    public function view(string $blockName) : string
    {
        $block = self::$blocks->where('handle', $blockName);

        $fileName = "{$block->pluck('blade')->first()}.blade.php";
        $pluginName = $block->pluck('plugin')->first();

        return "{$pluginName}/src/{$fileName}";
    }

    public function setUser(\WP_User $user)
    {
        if ($user->ID === 0) {
            return;
        }

        self::$View->setAuth(
            $user->data->user_nicename,
            $user->roles[0]
        );
    }
}
