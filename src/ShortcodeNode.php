<?php

namespace Beholdr\CommonmarkShortcode;

use League\CommonMark\Node\Inline\AbstractInline;

class ShortcodeNode extends AbstractInline
{
    public function __construct(
        public string $name,
        public array $attrs = [],
    ) {}
}
