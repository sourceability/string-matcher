<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher;

use Assert\Assertion;
use Functional as F;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\Model\ValueScore;
use function count;

class ValuesMatcher
{
    /**
     * @var array<int, MatcherInterface[]>
     */
    private $matchersByWeight = [];

    /**
     * @var int
     */
    private $minimumScore;

    /**
     * @param array<int, MatcherInterface[]> $matchersByWeight
     */
    public function __construct(
        array $matchersByWeight,
        int $minimumScore
    ) {
        foreach ($matchersByWeight as $weight => $matchers) {
            Assertion::allIsInstanceOf($matchers, MatcherInterface::class);
        }

        $this->matchersByWeight = $matchersByWeight;
        $this->minimumScore = $minimumScore;
    }

    /**
     * @param string[] $possibleMatches
     */
    public function getMatch(string $value, array $possibleMatches): ?string
    {
        $matches = $this->getMatchScores($value, $possibleMatches);
        if (count($matches) < 1) {
            return null;
        }

        $topScoringMatch = F\first($matches);

        return $topScoringMatch->getValue();
    }

    /**
     * @param string[] $possibleMatches
     *
     * @return string[]
     */
    public function getMatches(string $value, array $possibleMatches): array
    {
        return F\map(
            $this->getMatchScores($value, $possibleMatches),
            static function (ValueScore $match): string {
                return $match->getValue();
            }
        );
    }

    /**
     * @param string[] $possibleMatches
     *
     * @return ValueScore[]
     */
    private function getMatchScores(string $value, array $possibleMatches): array
    {
        $finalScores = [];
        foreach ($possibleMatches as $possibleMatch) {
            $score = 0;
            foreach ($this->matchersByWeight as $weight => $matchers) {
                foreach ($matchers as $matcher) {
                    $score += $matcher->score($value, $possibleMatch) * $weight;
                }
            }

            if ($score < $this->minimumScore) {
                continue;
            }

            $finalScores[] = new ValueScore($possibleMatch, $score);
        }

        $finalScores = F\sort(
            $finalScores,
            static function (ValueScore $matchA, ValueScore $matchB): int {
                return $matchB->getScore() <=> $matchA->getScore();
            }
        );

        return $finalScores;
    }
}
