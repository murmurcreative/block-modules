# Block Modules

Plugin backbone for modular block building using Laravel Blade and React.

## Usage

- Install this.

- Scaffold your block plugin:

```bash
plugins/example-block
├── example-block.php
└── src
    ├── blade
    │   └── render.blade.php
    └── index.js
```

The important bit is that the registration process assumes your blade partials and JS can be found in the `{plugin-name}/src` directory.

## Register a block

```php
add_filter('register_blockmodules', function ($blocks) {
    $blocks->push([
        'plugin' => 'example-block',
        'handle' => 'example/block',
        'entry'  => 'index.js',
        'blade'  => 'blade/render',
    ]);

    return $blocks;
});
```

- `plugin` should match the name of your plugin's directory.
- `handle` should match the handle you used in your JS registration
- `entry` is your main JS file, relative to `{plugin-name}/src`
- `blade` is your blade view for the frontend, relative to `{plugin-name}/src`.

## Write a view

Now you can use the specified blade partial to render your block. You'll find the block attributes and inner content already waiting for you.

```php
<h2>{!! $attr->heading !!}</h2>
<span>{!! $attr->accentText !!}</span>

<div class="innerBlocks">
  {!! $content !!}
</div>
```

## Other tricks

Do different stuff depending on the access level of the user:

```php
@auth('administrator')
  <p>This user is an admin!</p>
@endauth

@guest
  <p>Only guests can see this.</p>
@endguest
```



## Blade for everyone

Under the hood this plugin uses [EFTEC\BladeOne](https://github.com/EFTEC/BladeOne), a minimalist blade implementation that does not require any particular plugin or framework and has zero dependencies.

## Example

Certainly. Example usage is included in the `example-block` dir of this repo.

&copy; 2019 tiny pixel collective, llc

licensed MIT
