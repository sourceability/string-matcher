<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Simplifier;

interface SimplifierInterface
{
    public function simplify(string $value): string;
}
