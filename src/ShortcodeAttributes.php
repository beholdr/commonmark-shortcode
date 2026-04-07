<?php

namespace Beholdr\CommonmarkShortcode;

final class ShortcodeAttributes
{
    public static function parse(string $value): array
    {
        $squished = preg_replace('~(\s|\x{3164}|\x{1160})+~u', ' ', trim($value));

        if ($squished === '' || $squished === null) {
            return [];
        }

        $values = explode(' ', $squished);
        $attrs = [];

        foreach ($values as $el) {
            if (! str_contains($el, '=')) {
                $attrs[$el] = true;

                continue;
            }

            [$key, $val] = explode('=', $el, 2);
            $attrs[$key] = $val;
        }

        return $attrs;
    }

    public static function stringify(array $attributes): string
    {
        $values = [];

        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $values[] = (string) $key;

                continue;
            }

            $values[] = sprintf('%s=%s', (string) $key, (string) $value);
        }

        return implode(' ', $values);
    }
}
