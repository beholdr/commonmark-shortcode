<?php

namespace Beholdr\CommonmarkShortcode;

use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

class ShortcodeParser implements InlineParserInterface
{
    public function __construct(private ShortcodeRegistry $registry) {}

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex('\[([-_a-z0-9]+)(?:\s+([^\]]+))?\]');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $matches = $inlineContext->getMatches();

        $name = $matches[1];

        if (! $this->registry->has($name)) {
            return false;
        }

        $attrs = ! empty($matches[2]) ? ShortcodeAttributes::parse($matches[2]) : [];

        $node = new ShortcodeNode($name, $attrs);
        $inlineContext->getContainer()->appendChild($node);

        $inlineContext->getCursor()->advanceBy(strlen($matches[0]));

        return true;
    }
}
