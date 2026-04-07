# CommonmarkShortcode

[![Latest Version on Packagist](https://img.shields.io/packagist/v/beholdr/commonmark-shortcode.svg?style=flat-square)](https://packagist.org/packages/beholdr/commonmark-shortcode)

[Shortcodes](https://codex.wordpress.org/Shortcode) extension for [CommonMark](https://commonmark.thephpleague.com/).

## Installation

You can install the package via composer:

```bash
composer require beholdr/commonmark-shortcode
```

## Usage

```php
use Beholdr\CommonmarkShortcode\ShortcodeExtension;
use Beholdr\CommonmarkShortcode\ShortcodeRegistry;
use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter;

// create shortcodes registry
$registry = new ShortcodeRegistry();
$registry->register('my-code', fn (array $attrs) => /* Your callback */);

// register extension
$environment = new Environment();
$environment->addExtension(new ShortcodeExtension($registry));

// use it in markdown
$converter = new MarkdownConverter($environment);
echo $converter->convert('Markdown with [my-code]!');
```

You can rebuild a shortcode attribute string using `stringify` helper:

```php
use Beholdr\CommonmarkShortcode\ShortcodeAttributes;

$attrs = ['foo' => 'bar', 'enabled' => true];
ShortcodeAttributes::stringify($attrs); // `foo=bar enabled`
```

### Laravel example

You can use this extension with [graham-campbell/markdown](https://github.com/GrahamCampbell/Laravel-Markdown) or [spatie/laravel-markdown](https://github.com/spatie/laravel-markdown) packages.

1. Register extension in the markdown package config:

```php
'extensions' => [
    Beholdr\CommonmarkShortcode\ShortcodeExtension::class,
],
```

2. Bind `ShortcodeRegistry` as singleton inside your `AppServiceProvider` and register your shortcodes:

```php
use Beholdr\CommonmarkShortcode\ShortcodeAttributes;
use Beholdr\CommonmarkShortcode\ShortcodeRegistry;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ShortcodeRegistry::class, fn () => new ShortcodeRegistry);
    }

    public function boot(): void
    {
        // register `[calculator attr=value]` shortcode
        // to render livewire component with given props
        app(ShortcodeRegistry::class)
            ->register('calculator', fn ($attrs) =>
                Blade::render(
                    sprintf(
                        '<livewire:calculator %s />',
                        ShortcodeAttributes::stringify($attrs)
                    )
                )
            );
    }
}
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
