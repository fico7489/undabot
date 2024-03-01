<?php

namespace App\Tests\Service\TextTerm;

use App\Service\TextTerm\ScoreCalculator;
use App\Tests\TestCase;

class ScoreCalculatorTest extends TestCase
{
    public function testService(): void
    {
        /** @var ScoreCalculator $scoreCalculator */
        $scoreCalculator = $this->container->get(ScoreCalculator::class);

        $this->assertEquals(2.5, $scoreCalculator->calculate('test php sucks sd asd php sucks adaphp aaa php sucks,, php rocks done.', 'php'));
    }
}
