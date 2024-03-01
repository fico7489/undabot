<?php

namespace App\Service\TextTerm;

class ScoreCalculator
{
    public function calculate(string $text, string $term): float
    {
        $scorePositive = substr_count($text, $term.' rocks');
        $scoreNegative = substr_count($text, $term.' sucks');
        $scoreAll = $scorePositive + $scoreNegative;

        if (0 === $scoreAll) {
            return 0;
        }

        return round($scorePositive / $scoreAll, 2) * 10;
    }
}
