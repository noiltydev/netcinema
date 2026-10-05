<?php

declare(strict_types=1);

function slugify(string $string): string
{
    return (string)Str::of($string)->replace(['&', '18+'], ['and', 'r-plus'])->slug();
}

/**
 * Simple, non-cryptographically secure hash function for strings.
 * This is used for generating hashes for identifiers that do not require high security.
 */
function simple_hash(?string $string): string
{
    return md5("noilty:$string");
}

/**
 * @param string|int ...$parts
 */
function cache_key(...$parts): string
{
    return simple_hash(implode('.', $parts));
}
