<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Simplifier;

use function mb_strtolower;
use function trim;

class TrimmedLowercaseSimplifier implements SimplifierInterface
{
    public function simplify(string $value): string
    {
        $result = mb_strtolower($value);

        return trim($result);
    }
}
