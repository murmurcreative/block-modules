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

## Or, don't write a view

Don't set a value for blade if you want to render with JS.

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

## Tweak settings with filters (optional)

Modify where cached views are stored:

```php
add_filter('cache_path_blockmodules', function ($cachePath) {
  return '/cache/to/this/dir';
});
```

Change the base directory used to located view templates:

```php
add_filter('base_path_blockmodules', function ($basePath) {
  return '/views/relative/from/this/dir';
});
```

Disable user functions (saves the database call):

```php
add_filter('disable_user_blockmodules', function () {
  return true;
});
```

Enable view debugger:

```php
add_filter('debug_blockmodules', function () {
  return true;
})
```

## Blade for everyone

Under the hood this plugin uses [EFTEC\BladeOne](https://github.com/EFTEC/BladeOne), a minimalist blade implementation that does not require any particular plugin or framework and has zero dependencies.

## Example

Certainly. Example usage is included in the `example-block` dir of this repo.

&copy; 2019 tiny pixel collective, llc

licensed MIT
