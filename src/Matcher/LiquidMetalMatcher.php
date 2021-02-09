<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Matcher;

use Sourceability\StringMatcher\Simplifier\SimplifierInterface;
use Sourceability\StringMatcher\Strategy\LiquidMetal;
use function mb_strlen;
use function trim;

class LiquidMetalMatcher implements MatcherInterface
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

        if (mb_strlen(trim($input)) < 1 || mb_strlen(trim($potential)) < 1) {
            return 0;
        }

        return LiquidMetal::score($potential, $input);
    }
}
