<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Sourceability\StringMatcher\Matcher\LiquidMetalMatcher;
use Sourceability\StringMatcher\Simplifier\AlphanumericSimplifier;
use Sourceability\StringMatcher\Simplifier\ChainSimplifier;
use Sourceability\StringMatcher\Simplifier\TrimmedLowercaseSimplifier;
use Sourceability\StringMatcher\ValuesMatcherBuilder;
use const PHP_EOL;
use function print_r;

$chainSimplifier = new ChainSimplifier([
    new TrimmedLowercaseSimplifier(),
    new AlphanumericSimplifier(),
]);

$matcher = (new ValuesMatcherBuilder())
    ->addDefaultMatchers()
    ->addMatcher(new LiquidMetalMatcher($chainSimplifier), 50)
    ->setMinimumScore(50)
    ->create();

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
