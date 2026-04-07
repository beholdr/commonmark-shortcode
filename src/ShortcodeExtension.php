<?php

namespace Beholdr\CommonmarkShortcode;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class ShortcodeExtension implements ExtensionInterface
{
    public function __construct(private ShortcodeRegistry $registry) {}

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addInlineParser(new ShortcodeParser($this->registry), 99)
            ->addRenderer(ShortcodeNode::class, new ShortcodeRenderer($this->registry));
    }
}
