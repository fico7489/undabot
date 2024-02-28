<?php

namespace App\Service;

class ScoreCalculator
{
    public function match(string $text, string $keyword): float
    {
        $scorePositive = substr_count($text, $keyword.' rocks');
        $scoreNegative = substr_count($text, $keyword.' sucks');
        $scoreAll = $scorePositive + $scoreNegative;

        return round($scorePositive / $scoreAll, 2) * 10;
    }
}
