<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Matcher;

use PHPUnit\Framework\TestCase;
use Sourceability\StringMatcher\Matcher\MatcherInterface;

abstract class AbstractMatcherTest extends TestCase
{
    abstract public function getTestMatchData(): \Iterator;

    abstract public function getMatcher(): MatcherInterface;

    /**
     * @dataProvider getTestMatchData
     */
    public function testMatch($referenceManufacturer, $inputManufacturer, $expectedValue): void
    {
        $matcher = $this->getMatcher();

        self::assertSame($expectedValue, $matcher->score($inputManufacturer, $referenceManufacturer));
    }
}
