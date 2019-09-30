<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Simplifier;

class ChainSimplifier implements SimplifierInterface
{
    /**
     * @var SimplifierInterface[]
     */
    private $simplifiers;

    /**
     * @param SimplifierInterface[] $simplifiers
     */
    public function __construct(array $simplifiers)
    {
        $this->simplifiers = $simplifiers;
    }

    public function simplify(string $value): string
    {
        $result = $value;
        foreach ($this->simplifiers as $simplifier) {
            $result = $simplifier->simplify($result);
        }

        return $result;
    }
}
