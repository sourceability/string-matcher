<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Simplifier;

use Functional as F;
use PHPUnit\Framework\TestCase;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\ValuesMatcher;

class ValuesMatcherTest extends TestCase
{
    public function getTestData(): \Iterator
    {
        $value = 'Test Value';
        $possibleMatches = [
            'Test Match',
            'Non Match',
        ];

        $matcherOne = $this->prophesize(MatcherInterface::class);
        $matcherOne->score($value, 'Test Match')->willReturn(0.5)->shouldBeCalled();
        $matcherOne->score($value, 'Non Match')->willReturn(0)->shouldBeCalled();

        $matcherTwo = $this->prophesize(MatcherInterface::class);
        $matcherTwo->score($value, 'Test Match')->willReturn(0.5)->shouldBeCalled();
        $matcherTwo->score($value, 'Non Match')->willReturn(0)->shouldBeCalled();

        $matcherThree = $this->prophesize(MatcherInterface::class);
        $matcherThree->score($value, 'Test Match')->willReturn(0.25)->shouldBeCalled();
        $matcherThree->score($value, 'Non Match')->willReturn(0.50)->shouldBeCalled();

        $matchers = [
            100 => [$matcherOne->reveal()],
            75 => [$matcherTwo->reveal(), $matcherThree->reveal()],
        ];

        yield [
            $matchers,
            100,
            $value,
            $possibleMatches,
            [
                'Test Match',
            ],
        ];

        yield [
            $matchers,
            20,
            $value,
            $possibleMatches,
            [
                'Test Match',
                'Non Match',
            ],
        ];
    }

    /**
     * @dataProvider getTestData
     */
    public function test(array $matchers, int $minimumScore, string $value, array $possibleMatches, array $expectedResults): void
    {
        $valuesMatcher = new ValuesMatcher(
            $matchers,
            $minimumScore
        );

        $results = $valuesMatcher->getMatches($value, $possibleMatches);

        self::assertSame($expectedResults, $results);

        $result = $valuesMatcher->getMatch($value, $possibleMatches);

        self::assertSame(F\first($expectedResults), $result);
    }
}
