<?php

namespace Beholdr\CommonmarkShortcode;

use InvalidArgumentException;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class ShortcodeRenderer implements NodeRendererInterface
{
    public function __construct(private ShortcodeRegistry $registry) {}

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (! ($node instanceof ShortcodeNode)) {
            throw new InvalidArgumentException;
        }

        $handler = $this->registry->get($node->name);

        return $handler ? $handler($node->attrs) : '';
    }
}
