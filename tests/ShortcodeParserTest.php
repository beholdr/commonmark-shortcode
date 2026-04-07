<?php

use Beholdr\CommonmarkShortcode\ShortcodeNode;
use Beholdr\CommonmarkShortcode\ShortcodeParser;
use Beholdr\CommonmarkShortcode\ShortcodeRegistry;
use Beholdr\CommonmarkShortcode\ShortcodeRenderer;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

function makeConverter(ShortcodeRegistry $registry): MarkdownConverter
{
    $environment = new Environment;
    $environment
        ->addExtension(new CommonMarkCoreExtension)
        ->addInlineParser(new ShortcodeParser($registry), 99)
        ->addRenderer(ShortcodeNode::class, new ShortcodeRenderer($registry));

    return new MarkdownConverter($environment);
}

it('renders a registered shortcode', function () {
    $registry = new ShortcodeRegistry;
    $registry->register('hello', fn (): string => 'Hello world');

    $converter = makeConverter($registry);

    expect($converter->convert('[hello]')->getContent())->toBe("<p>Hello world</p>\n");
});

it('leaves unknown shortcodes as literal markdown text', function () {
    $registry = new ShortcodeRegistry;
    $converter = makeConverter($registry);

    expect($converter->convert('[missing]')->getContent())->toBe("<p>[missing]</p>\n");
});

it('passes an empty attribute array when the shortcode has no attributes', function () {
    $receivedAttrs = null;
    $registry = new ShortcodeRegistry;
    $registry->register('hello', function (array $attrs) use (&$receivedAttrs): string {
        $receivedAttrs = $attrs;

        return 'ok';
    });

    $converter = makeConverter($registry);
    $converter->convert('[hello]');

    expect($receivedAttrs)->toBe([]);
});

it('parses key value and flag attributes into an associative array', function () {
    $receivedAttrs = null;
    $registry = new ShortcodeRegistry;
    $registry->register('hello', function (array $attrs) use (&$receivedAttrs): string {
        $receivedAttrs = $attrs;

        return 'ok';
    });

    $converter = makeConverter($registry);
    $converter->convert('[hello foo=bar enabled answer=42]');

    expect($receivedAttrs)->toBe([
        'foo' => 'bar',
        'enabled' => true,
        'answer' => '42',
    ]);
});

it('squishes supported unicode whitespace between attributes', function () {
    $receivedAttrs = null;
    $registry = new ShortcodeRegistry;
    $registry->register('hello', function (array $attrs) use (&$receivedAttrs): string {
        $receivedAttrs = $attrs;

        return 'ok';
    });

    $converter = makeConverter($registry);
    $converter->convert("[hello foo=bar\u{3164}enabled\u{1160}answer=42]");

    expect($receivedAttrs)->toBe([
        'foo' => 'bar',
        'enabled' => true,
        'answer' => '42',
    ]);
});
