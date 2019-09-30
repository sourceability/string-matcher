<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Matcher;

use Sourceability\StringMatcher\Simplifier\SimplifierInterface;
use function array_intersect;
use function count;
use function max;
use function preg_split;

class SimpleFuzzyStringMatcher implements MatcherInterface
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
        $input = $this->stringSimplifier->simplify($input);
        $potential = $this->stringSimplifier->simplify($potential);

        $input = preg_split('/(\s+)/', $input) ?: [];
        $potential = preg_split('/(\s+)/', $potential) ?: [];

        $longestLength = max(count($input), count($potential));

        if ($longestLength < 1) {
            return 0;
        }

        $result = count(
            array_intersect(
                $input,
                $potential
            )
        );

        return $result / $longestLength;
    }
}
