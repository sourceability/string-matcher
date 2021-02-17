<?php

declare(strict_types=1);

namespace Sourceability\StringMatcher\Strategy;

use function array_slice;
use function count;
use function ctype_upper;
use function mb_strlen;
use function mb_strpos;
use function mb_strtolower;

/**
 * LiquidMetal, version: 1.3.0 (2014-08-19).
 *
 * A mimetic poly-alloy of Quicksilver's scoring algorithm, essentially
 * LiquidMetal.
 *
 * For usage and examples, visit:
 * http://github.com/kjantzer/liquidmetal-php
 *
 * Licensed under the MIT:
 * http://www.opensource.org/licenses/mit-license.php
 * Copyright (c) 2009-2014, Ryan McGeary (ryan -[at]- mcgeary [*dot*] org).
 * Authors Ryan McGeary, Kevin Jantzer.
 */
class LiquidMetal
{
    /**
     * @var float
     */
    private static $SCORE_NO_MATCH = 0.0;
    /**
     * @var float
     */
    private static $SCORE_MATCH = 1.0;
    /**
     * @var float
     */
    private static $SCORE_TRAILING = 0.8;
    /**
     * @var float
     */
    private static $SCORE_TRAILING_BUT_STARTED = 0.9;
    /**
     * @var float
     */
    private static $SCORE_BUFFER = 0.85;
    /**
     * @var string
     */
    private static $WORD_SEPARATORS = " \t_-";

    public static function score(string $string, string $abbrev): float
    {
        // short circuits
        if (0 === mb_strlen($abbrev)) {
            return self::$SCORE_TRAILING;
        }

        if (mb_strlen($abbrev) > mb_strlen($string)) {
            return self::$SCORE_NO_MATCH;
        }

        // match & score all
        $scores = [];
        $allScores = [];
        $search = mb_strtolower($string);
        $abbrev = mb_strtolower($abbrev);
        self::scoreAll($string, $search, $abbrev, -1, 0, $scores, $allScores);

        // complete miss
        if (0 === count($allScores)) {
            return 0;
        }

        // sum per-character $scores into overall $scores,
        // selecting the maximum score
        $maxScore = 0.0;
        $maxArray = [];

        for ($i = 0; $i < count($allScores); ++$i) {
            $scores = $allScores[$i];
            $scoreSum = 0.0;
            for ($j = 0; $j < mb_strlen($string); ++$j) {
                $scoreSum += $scores[$j];
            }

            if ($scoreSum <= $maxScore) {
                continue;
            }

            $maxScore = $scoreSum;
            $maxArray = $scores;
        }

        // normalize max score by $string length
        // s. t. the perfect match score = 1
        $maxScore /= mb_strlen($string);

        return $maxScore;
    }

    /**
     * @param float[] $scores
     * @param float[] $allScores
     */
    private static function scoreAll(
        string $string,
        string $search,
        string $abbrev,
        int $searchIndex,
        int $abbrIndex,
        array &$scores,
        array &$allScores
    ): void {
        // save completed match $scores at end of $search
        if ($abbrIndex === mb_strlen($abbrev)) {
            // add trailing score for the remainder of the match
            $started = ($search[0] === $abbrev[0]);
            $trailScore = $started ? self::$SCORE_TRAILING_BUT_STARTED : self::$SCORE_TRAILING;
            self::fillArray($scores, $trailScore, count($scores), mb_strlen($string));
            // save score clone (since reference is persisted in $scores)
            $allScores[] = array_slice($scores, 0);

            return;
        }

        // consume current char to match
        $char = $abbrev[$abbrIndex];
        ++$abbrIndex;

        // cancel match if a character is missing
        if ($searchIndex < 0) {
            $index = mb_strpos($search, $char, 0);
        } else {
            $index = mb_strpos($search, $char, $searchIndex);
        }

        if (false === $index) {
            return;
        }

        // match all instances of the $abbreviaton char
        $scoreIndex = $searchIndex; // score section to update
        while (false !== $index = mb_strpos($search, $char, $searchIndex + 1)) {
            // score this match according to context
            if (self::isNewWord($string, $index)) {
                $scores[$index - 1] = 1;
                self::fillArray($scores, self::$SCORE_BUFFER, $scoreIndex + 1, $index - 1);
            } elseif (self::isUpperCase($string, $index)) {
                self::fillArray($scores, self::$SCORE_BUFFER, $scoreIndex + 1, $index);
            } else {
                self::fillArray($scores, self::$SCORE_NO_MATCH, $scoreIndex + 1, $index);
            }

            $scores[$index] = self::$SCORE_MATCH;

            // consume matched $string and continue $search
            $searchIndex = $index;
            self::scoreAll($string, $search, $abbrev, $searchIndex, $abbrIndex, $scores, $allScores);
        }
    }

    private static function isUpperCase(string $string, int $index): bool
    {
        $c = $string[$index];

        return ctype_upper($c);
    }

    private static function isNewWord(string $string, int $index): bool
    {
        if (0 === $index || false === mb_strpos(self::$WORD_SEPARATORS, $string[$index - 1])) {
            return false;
        }

        return true;
    }

    /**
     * @param float[] $array
     *
     * @return float[]
     */
    private static function fillArray(array &$array, float $value, int $from, int $to): array
    {
        for ($i = $from; $i < $to; ++$i) {
            $array[$i] = $value;
        }

        return $array;
    }
}
