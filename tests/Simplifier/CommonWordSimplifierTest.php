<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Simplifier;

use PHPUnit\Framework\TestCase;
use Sourceability\StringMatcher\Simplifier\CommonWordSimplifier;

class CommonWordSimplifierTest extends TestCase
{
    public function getTestData(): \Iterator
    {
        yield ['microsoft', 'microsoft'];
        yield ['microsoft corp', 'microsoft'];
        yield ['microsoft corp usa', 'microsoft usa'];
        yield ['technologies corp', 'technologies corp'];
    }

    /**
     * @dataProvider getTestData
     */
    public function testMatch($input, $expectedOutput): void
    {
        $simplifier = new CommonWordSimplifier([
            'corp',
            'technologies',
        ]);

        self::assertSame($expectedOutput, $simplifier->simplify($input));
    }
}
