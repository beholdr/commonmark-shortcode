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
$registry->register('my-code', fn () => (/* Your callback */));

// register extension
$environment = new Environment();
$environment->addExtension(new ShortcodeExtension($registry));

// use it in markdown
$converter = new MarkdownConverter($environment);
echo $converter->convert('Markdown with [my-code]!');
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
