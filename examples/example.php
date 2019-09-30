<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Sourceability\StringMatcher\Matcher\AbbreviationMatcher;
use Sourceability\StringMatcher\Matcher\LevenshteinMatcher;
use Sourceability\StringMatcher\Matcher\LiquidMetalMatcher;
use Sourceability\StringMatcher\Matcher\MetaphoneMatcher;
use Sourceability\StringMatcher\Matcher\SimpleFuzzyStringMatcher;
use Sourceability\StringMatcher\Matcher\SimpleStringMatcher;
use Sourceability\StringMatcher\Simplifier\AlphanumericSimplifier;
use Sourceability\StringMatcher\Simplifier\ChainSimplifier;
use Sourceability\StringMatcher\Simplifier\CommonWordSimplifier;
use Sourceability\StringMatcher\Simplifier\TrimmedLowercaseSimplifier;
use Sourceability\StringMatcher\ValuesMatcher;

$chainSimplifier = new ChainSimplifier([
    new TrimmedLowercaseSimplifier(),
    new AlphanumericSimplifier(),
    new CommonWordSimplifier([
        'USA',
    ]),
]);

$matcher = new ValuesMatcher(
    [
        100 => [
            new SimpleStringMatcher($chainSimplifier),
            new AbbreviationMatcher($chainSimplifier, 2, 0.25),
        ],
        75 => [
            new LevenshteinMatcher($chainSimplifier, 1, 1, 1),
            new MetaphoneMatcher($chainSimplifier, 1, 1, 1),
            new SimpleFuzzyStringMatcher($chainSimplifier),
        ],
        50 => [
            new LiquidMetalMatcher($chainSimplifier),
        ],
    ],
    50
);

$input = 'Kraft Mac & Cheese';
$matches = [
    'Wine & Cheese',
    'Apple iMac',
    'Craft Fair',
    'Macaroni and Cheese',
    'Kraft Macaroni and Cheese',
    'Something Unexpected',
];

echo "Best Match Result: \r\n";
echo $matcher->getMatch($input, $matches);

echo PHP_EOL.PHP_EOL;

echo "Multiple Match Result (ordered by likelihood of match): \r\n";
print_r($matcher->getMatches($input, $matches));
