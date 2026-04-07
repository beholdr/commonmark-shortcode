<?php

use Beholdr\CommonmarkShortcode\ShortcodeRegistry;

it('registers and resolves handlers by shortcode name', function () {
    $registry = new ShortcodeRegistry;
    $handler = fn (array $attrs): string => $attrs['name'] ?? 'missing';

    $registry->register('hello', $handler);

    expect($registry->has('hello'))->toBeTrue();
    expect($registry->get('hello'))->toBe($handler);
    expect(($registry->get('hello'))(['name' => 'world']))->toBe('world');
});

it('returns null and false for unknown shortcodes', function () {
    $registry = new ShortcodeRegistry;

    expect($registry->has('missing'))->toBeFalse();
    expect($registry->get('missing'))->toBeNull();
});
