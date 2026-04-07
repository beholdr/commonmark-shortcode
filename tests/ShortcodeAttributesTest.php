<?php

use Beholdr\CommonmarkShortcode\ShortcodeAttributes;

it('parses an empty attribute string into an empty array', function () {
    expect(ShortcodeAttributes::parse(''))->toBe([]);
});

it('parses key value and flag attributes into an associative array', function () {
    expect(ShortcodeAttributes::parse('foo=bar enabled answer=42'))->toBe([
        'foo' => 'bar',
        'enabled' => true,
        'answer' => '42',
    ]);
});

it('squishes supported unicode whitespace between attributes', function () {
    expect(ShortcodeAttributes::parse("foo=bar\u{3164}enabled\u{1160}answer=42"))->toBe([
        'foo' => 'bar',
        'enabled' => true,
        'answer' => '42',
    ]);
});

it('stringifies an empty attribute array into an empty string', function () {
    expect(ShortcodeAttributes::stringify([]))->toBe('');
});

it('stringifies key value and flag attributes while preserving order', function () {
    expect(ShortcodeAttributes::stringify([
        'foo' => 'bar',
        'enabled' => true,
        'answer' => '42',
    ]))->toBe('foo=bar enabled answer=42');
});

it('round trips simple attributes through stringify and parse', function () {
    $attributes = [
        'foo' => 'bar',
        'enabled' => true,
        'answer' => '42',
    ];

    expect(ShortcodeAttributes::parse(ShortcodeAttributes::stringify($attributes)))->toBe($attributes);
});
