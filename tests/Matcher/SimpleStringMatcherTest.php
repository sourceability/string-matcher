<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Matcher;

use Prophecy\Argument;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\Matcher\SimpleStringMatcher;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;

class SimpleStringMatcherTest extends AbstractMatcherTest
{
    public function getTestMatchData(): \Iterator
    {
        yield ['Consumer Value Stores', 'CVS', 0.0];
        yield ['Consumer Value Stores', 'Consumer Value Stores', 1.0];
    }

    public function getMatcher(): MatcherInterface
    {
        $simplifier = $this->prophesize(SimplifierInterface::class);
        $simplifier->simplify(Argument::any())->will(function ($arguments) {
            return $arguments[0];
        });

        return new SimpleStringMatcher($simplifier->reveal());
    }
}
