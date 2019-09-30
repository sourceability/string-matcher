<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Matcher;

use Sourceability\StringMatcher\Matcher\Utility\StringMatcher;
use Sourceability\StringMatcher\Simplifier\SimplifierInterface;
use function levenshtein;

class LevenshteinMatcher implements MatcherInterface
{
    /**
     * @var SimplifierInterface
     */
    private $stringSimplifier;

    /**
     * @var int
     */
    private $costOfInsert;

    /**
     * @var int
     */
    private $costOfReplace;

    /**
     * @var int
     */
    private $costOfDelete;

    public function __construct(
        SimplifierInterface $stringSimplifier,
        int $costOfInsert,
        int $costOfReplace,
        int $costOfDelete
    ) {
        $this->stringSimplifier = $stringSimplifier;
        $this->costOfInsert = $costOfInsert;
        $this->costOfReplace = $costOfReplace;
        $this->costOfDelete = $costOfDelete;
    }

    public function score(string $input, string $potential): float
    {
        $input = $this->stringSimplifier->simplify($input);
        $potential = $this->stringSimplifier->simplify($potential);

        $result = levenshtein($input, $potential, $this->costOfInsert, $this->costOfReplace, $this->costOfDelete);

        $longestLength = StringMatcher::getLongestLength($input, $potential);
        if ($result > $longestLength) {
            return 0;
        }

        if (0 === $longestLength) {
            return 0;
        }

        return 1 - ($result / $longestLength);
    }
}
