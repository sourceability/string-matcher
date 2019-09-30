<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Simplifier;

use Assert\Assertion;
use function array_map;
use function implode;
use function mb_strlen;
use function preg_replace;
use function sprintf;
use function trim;
use function usort;

class CommonWordSimplifier implements SimplifierInterface
{
    /**
     * @var string[]
     */
    private $words = [];

    /**
     * @var string
     */
    private $regex;

    /**
     * @param string[] $words
     */
    public function __construct(array $words)
    {
        $this->words = $words;

        usort($this->words, static function ($a, $b) {
            return mb_strlen($b) <=> mb_strlen($a);
        });

        $this->regex = sprintf('/\b(?:%s)\b/', implode('|', array_map('preg_quote', $this->words)));
    }

    public function simplify(string $value): string
    {
        $result = preg_replace(
            [
                $this->regex,
                '/\s+/',
            ],
            [
                '',
                ' ', //This is intentionally a space so that multiple spaces are converted to single spaces
            ],
            $value
        );

        Assertion::string($result);

        $result = trim($result);

        Assertion::string($result);

        if (0 === mb_strlen($result)) {
            return $value;
        }

        return $result;
    }
}
