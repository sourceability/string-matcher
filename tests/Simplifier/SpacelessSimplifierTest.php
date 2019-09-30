<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Simplifier;

use PHPUnit\Framework\TestCase;
use Sourceability\StringMatcher\Simplifier\SpacelessSimplifier;

class SpacelessSimplifierTest extends TestCase
{
    public function getTestData(): \Iterator
    {
        yield ['microsoft', 'microsoft'];
        yield ['microsoft usa', 'microsoftusa'];
    }

    /**
     * @dataProvider getTestData
     */
    public function testMatch($input, $expectedOutput): void
    {
        $simplifier = new SpacelessSimplifier();

        self::assertSame($expectedOutput, $simplifier->simplify($input));
    }
}
