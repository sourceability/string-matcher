<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Simplifier\Utility;

use PHPUnit\Framework\TestCase;
use Sourceability\StringMatcher\Matcher\Utility\StringMatcher;

class StringMatcherTest extends TestCase
{
    public function getTestMatchData(): \Iterator
    {
        yield ['microsoft', '', false];
        yield ['', 'microsoft', false];
        yield ['microsoft', 'microsoft', true];
        yield ['cvs', 'microsoft', false];
    }

    /**
     * @dataProvider getTestMatchData
     */
    public function testMatch(string $inputOne, string $inputTwo, bool $expectedResult): void
    {
        self::assertSame($expectedResult, StringMatcher::doStringsMatch($inputOne, $inputTwo));
    }

    public function getTestLongestLengthData(): \Iterator
    {
        yield ['microsoft', 'microsoft', 9];
        yield ['cvs', 'microsoft', 9];
        yield ['microsoft', 'cvs', 9];
    }

    /**
     * @dataProvider getTestLongestLengthData
     */
    public function testLongestLength(string $inputOne, string $inputTwo, int $expectedResult): void
    {
        self::assertSame($expectedResult, StringMatcher::getLongestLength($inputOne, $inputTwo));
    }
}
