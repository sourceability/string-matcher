<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Matcher;

use Prophecy\Argument;
use Sourceability\StringMatcher\Matcher\LiquidMetalMatcher;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;

class LiquidMetalMatcherTest extends AbstractMatcherTest
{
    public function getTestMatchData(): \Iterator
    {
        yield ['Consumer Value Stores', 'cvs', 0.8976190476190473];
        yield ['Consumer Value Stores', 'vcs', 0.0];
        yield ['Consumer Value Stores', '', 0.0];
        yield ['Consumer Value Stores', ' ', 0.0];
    }

    public function getMatcher(): MatcherInterface
    {
        $simplifier = $this->prophesize(SimplifierInterface::class);
        $simplifier->simplify(Argument::any())->will(function ($arguments) {
            return $arguments[0];
        });

        return new LiquidMetalMatcher($simplifier->reveal());
    }
}
