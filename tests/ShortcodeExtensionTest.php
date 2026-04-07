<?php

use Beholdr\CommonmarkShortcode\ShortcodeExtension;
use Beholdr\CommonmarkShortcode\ShortcodeRegistry;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

it('wires the parser and renderer into a commonmark environment', function () {
    $registry = new ShortcodeRegistry;
    $registry->register('hello', fn (): string => '<strong>Hello</strong>');

    $environment = new Environment;
    $environment->addExtension(new CommonMarkCoreExtension);
    $environment->addExtension(new ShortcodeExtension($registry));

    $converter = new MarkdownConverter($environment);

    expect($converter->convert('Before [hello] after')->getContent())
        ->toBe("<p>Before <strong>Hello</strong> after</p>\n");
});

it('does not break markdown conversion for unknown shortcodes', function () {
    $environment = new Environment;
    $environment->addExtension(new CommonMarkCoreExtension);
    $environment->addExtension(new ShortcodeExtension(new ShortcodeRegistry));

    $converter = new MarkdownConverter($environment);

    expect($converter->convert('Before [missing] after')->getContent())
        ->toBe("<p>Before [missing] after</p>\n");
});
