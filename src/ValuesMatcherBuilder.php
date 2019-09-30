<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher;

use Sourceability\StringMatcher\Matcher\AbbreviationMatcher;
use Sourceability\StringMatcher\Matcher\LevenshteinMatcher;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\Matcher\MetaphoneMatcher;
use Sourceability\StringMatcher\Matcher\SimpleFuzzyStringMatcher;
use Sourceability\StringMatcher\Matcher\SimpleStringMatcher;
use Sourceability\StringMatcher\Simplifier\AlphanumericSimplifier;
use Sourceability\StringMatcher\Simplifier\ChainSimplifier;
use Sourceability\StringMatcher\Simplifier\TrimmedLowercaseSimplifier;

class ValuesMatcherBuilder
{
    /**
     * @var array<int, array<MatcherInterface>>
     */
    private $matchers;

    /**
     * @var int
     */
    private $minimumScore;

    public function addMatcher(MatcherInterface $matcher, int $weight): self
    {
        $this->matchers[$weight][] = $matcher;

        return $this;
    }

    public function addDefaultMatchers(): self
    {
        $chainSimplifier = new ChainSimplifier([
            new TrimmedLowercaseSimplifier(),
            new AlphanumericSimplifier(),
        ]);

        $this->addMatcher(new SimpleStringMatcher($chainSimplifier), 100);
        $this->addMatcher(new AbbreviationMatcher($chainSimplifier, 2, 0.25), 100);
        $this->addMatcher(new LevenshteinMatcher($chainSimplifier, 1, 1, 1), 75);
        $this->addMatcher(new MetaphoneMatcher($chainSimplifier, 1, 1, 1), 75);
        $this->addMatcher(new SimpleFuzzyStringMatcher($chainSimplifier), 75);

        return $this;
    }

    public function setMinimumScore(int $minimumScore): self
    {
        $this->minimumScore = $minimumScore;

        return $this;
    }

    public function create(): ValuesMatcher
    {
        return new ValuesMatcher(
            $this->matchers,
            $this->minimumScore
        );
    }
}
