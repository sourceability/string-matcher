<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Simplifier;

use Assert\Assertion;
use function mb_strlen;
use function preg_replace;

class SpacelessSimplifier implements SimplifierInterface
{
    public function simplify(string $value): string
    {
        $result = preg_replace('/\s+/', '', $value);

        Assertion::string($result);

        if (0 === mb_strlen($result)) {
            return $value;
        }

        return $result;
    }
}
