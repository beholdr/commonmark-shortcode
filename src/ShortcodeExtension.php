<?php

namespace Beholdr\CommonmarkShortcode;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class ShortcodeExtension implements ExtensionInterface
{
    public function __construct(
        private ShortcodeRegistry $registry,
        private int $priority = 99,
    ) {}

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addInlineParser(new ShortcodeParser($this->registry), $this->priority)
            ->addRenderer(ShortcodeNode::class, new ShortcodeRenderer($this->registry));
    }
}
