<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Matcher;

use Sourceability\StringMatcher\Matcher\Utility\StringMatcher;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;
use function explode;
use function mb_substr;

class AbbreviationMatcher implements MatcherInterface
{
    /**
     * @var SimplifierInterface
     */
    private $stringSimplifier;

    /**
     * @var int
     */
    private $shortStringLength;

    /**
     * @var float
     */
    private $shortStringMatchScore;

    public function __construct(
        SimplifierInterface $stringSimplifier,
        int $shortStringLength,
        float $shortStringMatchScore
    ) {
        $this->stringSimplifier = $stringSimplifier;
        $this->shortStringLength = $shortStringLength;
        $this->shortStringMatchScore = $shortStringMatchScore;
    }

    public function score(string $input, string $potential): float
    {
        $input = $this->stringSimplifier->simplify($input);
        $potential = $this->stringSimplifier->simplify($potential);

        $abbreviatedInput = $this->abbreviate($input);
        $abbreviatedPotential = $this->abbreviate($potential);

        if (StringMatcher::doStringsMatch($input, $abbreviatedPotential)
            || StringMatcher::doStringsMatch($abbreviatedInput, $abbreviatedPotential)
        ) {
            if (StringMatcher::getLongestLength($abbreviatedInput, $abbreviatedPotential) < $this->shortStringLength) {
                return $this->shortStringMatchScore;
            }

            return 1;
        }

        return 0;
    }

    public function abbreviate(string $value): string
    {
        $result = '';

        $explodedValues = explode(' ', $value);
        foreach ($explodedValues as $explodedValue) {
            $result .= mb_substr($explodedValue, 0, 1);
        }

        return $result;
    }
}
