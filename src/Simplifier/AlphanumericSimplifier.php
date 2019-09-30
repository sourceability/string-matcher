<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Simplifier;

use Assert\Assertion;
use function mb_strlen;
use function preg_replace;
use function trim;

class AlphanumericSimplifier implements SimplifierInterface
{
    public function simplify(string $value): string
    {
        $result = preg_replace(
            '/[^A-Z0-9\s]/i',
            '',
            $value
        );

        Assertion::string($result);

        $result = trim($result);

        Assertion::string($result);

        if (0 === mb_strlen($result)) {
            return $value;
        }

        return $result;
    }
}
