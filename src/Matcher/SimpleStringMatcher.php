<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Matcher;

use Sourceability\StringMatcher\Matcher\Utility\StringMatcher;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;

class SimpleStringMatcher implements MatcherInterface
{
    /**
     * @var SimplifierInterface
     */
    private $stringSimplifier;

    public function __construct(SimplifierInterface $stringSimplifier)
    {
        $this->stringSimplifier = $stringSimplifier;
    }

    public function score(string $input, string $potential): float
    {
        if (StringMatcher::doStringsMatch($input, $potential)) {
            return 1;
        }

        $input = $this->stringSimplifier->simplify($input);
        $potential = $this->stringSimplifier->simplify($potential);

        return StringMatcher::doStringsMatch($input, $potential) ? 1 : 0;
    }
}
