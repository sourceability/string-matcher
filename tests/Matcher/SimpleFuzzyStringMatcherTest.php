<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Matcher;

use Prophecy\Argument;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\Matcher\SimpleFuzzyStringMatcher;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;

class SimpleFuzzyStringMatcherTest extends AbstractMatcherTest
{
    public function getTestMatchData(): \Iterator
    {
        yield ['Consumer Value Stores', 'Consumer Value Stores', 1.0];
        yield ['Consumer Value Shops', 'Consumer Value Stores', 1 - 1 / 3];
    }

    public function getMatcher(): MatcherInterface
    {
        $simplifier = $this->prophesize(SimplifierInterface::class);
        $simplifier->simplify(Argument::any())->will(function ($arguments) {
            return $arguments[0];
        });

        return new SimpleFuzzyStringMatcher($simplifier->reveal());
    }
}
