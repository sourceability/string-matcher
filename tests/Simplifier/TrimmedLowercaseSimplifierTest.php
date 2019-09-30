<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Simplifier;

use PHPUnit\Framework\TestCase;
use Sourceability\StringMatcher\Simplifier\TrimmedLowercaseSimplifier;

class TrimmedLowercaseSimplifierTest extends TestCase
{
    public function getTestData(): \Iterator
    {
        yield ['MiCrOsOfT', 'microsoft'];
        yield [' something with Spaces ', 'something with spaces'];
    }

    /**
     * @dataProvider getTestData
     */
    public function testMatch($input, $expectedOutput): void
    {
        $cleaner = new TrimmedLowercaseSimplifier();

        self::assertSame($expectedOutput, $cleaner->simplify($input));
    }
}
