<?php

namespace Beholdr\CommonmarkShortcode;

class ShortcodeRegistry
{
    protected array $handlers = [];

    public function register(string $name, callable $handler): ShortcodeRegistry
    {
        $this->handlers[$name] = $handler;

        return $this;
    }

    public function get(string $name): ?callable
    {
        return $this->handlers[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return ! empty($this->handlers[$name]);
    }
}
