<?php

use Beholdr\CommonmarkShortcode\ShortcodeNode;
use Beholdr\CommonmarkShortcode\ShortcodeRegistry;
use Beholdr\CommonmarkShortcode\ShortcodeRenderer;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

it('calls the registered handler with node attributes', function () {
    $registry = new ShortcodeRegistry;
    $registry->register('hello', fn (array $attrs): string => 'Hello '.$attrs['name']);

    $renderer = new ShortcodeRenderer($registry);
    $result = $renderer->render(
        new ShortcodeNode('hello', ['name' => 'world']),
        new class implements ChildNodeRendererInterface
        {
            public function renderNodes(iterable $nodes): string
            {
                return '';
            }

            public function getBlockSeparator(): string
            {
                return "\n";
            }

            public function getInnerSeparator(): string
            {
                return "\n";
            }
        },
    );

    expect($result)->toBe('Hello world');
});

it('returns an empty string when no handler is registered for the node', function () {
    $renderer = new ShortcodeRenderer(new ShortcodeRegistry);

    $result = $renderer->render(
        new ShortcodeNode('missing', ['name' => 'world']),
        new class implements ChildNodeRendererInterface
        {
            public function renderNodes(iterable $nodes): string
            {
                return '';
            }

            public function getBlockSeparator(): string
            {
                return "\n";
            }

            public function getInnerSeparator(): string
            {
                return "\n";
            }
        },
    );

    expect($result)->toBe('');
});

it('throws when rendering a node of the wrong type', function () {
    $renderer = new ShortcodeRenderer(new ShortcodeRegistry);

    expect(fn () => $renderer->render(
        new Document,
        new class implements ChildNodeRendererInterface
        {
            public function renderNodes(iterable $nodes): string
            {
                return '';
            }

            public function getBlockSeparator(): string
            {
                return "\n";
            }

            public function getInnerSeparator(): string
            {
                return "\n";
            }
        },
    ))->toThrow(InvalidArgumentException::class);
});
