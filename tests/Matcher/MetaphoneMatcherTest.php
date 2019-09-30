<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Matcher;

use Prophecy\Argument;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\Matcher\MetaphoneMatcher;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;

class MetaphoneMatcherTest extends AbstractMatcherTest
{
    public function getTestMatchData(): \Iterator
    {
        yield ['Electronic', 'Elektronic', 1.0];
        yield ['Aeroplane', 'Airplane', 1 - (1 / 5)];
        yield ['Violin', 'Guitar', 0.0];
    }

    public function getMatcher(): MatcherInterface
    {
        $simplifier = $this->prophesize(SimplifierInterface::class);
        $simplifier->simplify(Argument::any())->will(function ($arguments) {
            return $arguments[0];
        });

        return new MetaphoneMatcher($simplifier->reveal(), 1, 1, 1);
    }
}
