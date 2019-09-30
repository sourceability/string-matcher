<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Matcher;

use Prophecy\Argument;
use Sourceability\StringMatcher\Matcher\AbbreviationMatcher;
use Sourceability\StringMatcher\Matcher\MatcherInterface;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;

class AbbreviationMatcherTest extends AbstractMatcherTest
{
    public function getTestMatchData(): \Iterator
    {
        yield ['Consumer Value Stores', 'CVS', 1.0];
        yield ['Yoshida Manufacturing Corporation', 'YKK', 0.0];
    }

    public function getMatcher(): MatcherInterface
    {
        $simplifier = $this->prophesize(SimplifierInterface::class);
        $simplifier->simplify(Argument::any())->will(function ($arguments) {
            return $arguments[0];
        });

        return new AbbreviationMatcher($simplifier->reveal(), 2, 0.25);
    }
}
