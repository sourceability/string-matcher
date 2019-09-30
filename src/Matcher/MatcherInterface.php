<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Matcher;

interface MatcherInterface
{
    public function score(string $input, string $potential): float;
}
