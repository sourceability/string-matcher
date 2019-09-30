<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Simplifier;

use PHPUnit\Framework\TestCase;
use Sourceability\StringMatcher\Simplifier\AlphanumericSimplifier;

class AlphanumericSimplifierTest extends TestCase
{
    public function getTestData(): \Iterator
    {
        yield ['microsoft', 'microsoft'];
        yield ['c.v.s', 'cvs'];
        yield ['.... . .-.. .-.. ---', '.... . .-.. .-.. ---'];
    }

    /**
     * @dataProvider getTestData
     */
    public function testMatch($input, $expectedOutput): void
    {
        $simplifier = new AlphanumericSimplifier();

        self::assertSame($expectedOutput, $simplifier->simplify($input));
    }
}
