<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Matcher;

use Prophecy\Argument;
use Sourceability\StringMatcher\Matcher\LevenshteinMatcher;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;

class LevenshteinMatcherTest extends AbstractMatcherTest
{
    public function getTestMatchData(): \Iterator
    {
        yield ['Alpha1', 'Alpha2', 1 - (1 / 6)];
        yield ['Alpha', 'Beta', 1 - (4 / 5)];
    }

    public function getMatcher(): MatcherInterface
    {
        $simplifier = $this->prophesize(SimplifierInterface::class);
        $simplifier->simplify(Argument::any())->will(function ($arguments) {
            return $arguments[0];
        });

        return new LevenshteinMatcher($simplifier->reveal(), 1, 1, 1);
    }
}
