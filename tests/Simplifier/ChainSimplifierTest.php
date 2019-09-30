<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Tests\Simplifier;

use PHPUnit\Framework\TestCase;
use Sourceability\StringMatcher\Simplifier\ChainSimplifier;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;

class ChainSimplifierTest extends TestCase
{
    public function testMatch(): void
    {
        $input = 'Simpler-Test';
        $expectedOutput = 'simpler';

        $simplifierOne = $this->prophesize(SimplifierInterface::class);
        $simplifierOne->simplify($input)->willReturn('Simpler Test')->shouldBeCalled();

        $simplifierTwo = $this->prophesize(SimplifierInterface::class);
        $simplifierTwo->simplify('Simpler Test')->willReturn('simpler test')->shouldBeCalled();

        $simplifierThree = $this->prophesize(SimplifierInterface::class);
        $simplifierThree->simplify('simpler test')->willReturn($expectedOutput)->shouldBeCalled();

        $simplifier = new ChainSimplifier([
            $simplifierOne->reveal(),
            $simplifierTwo->reveal(),
            $simplifierThree->reveal(),
        ]);

        self::assertSame($expectedOutput, $simplifier->simplify($input));
    }
}
