<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Matcher\Utility;

use function max;
use function mb_strlen;

final class StringMatcher
{
    public static function doStringsMatch(string $one, string $two): bool
    {
        return mb_strlen($one) > 0
            && mb_strlen($two) > 0
            && $one === $two;
    }

    public static function getLongestLength(string $one, string $two): int
    {
        return max(mb_strlen($one), mb_strlen($two));
    }
}
