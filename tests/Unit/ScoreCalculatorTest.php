<?php

namespace App\Tests\Unit;

use App\Service\ScoreCalculator;
use App\Tests\TestCase;

class ScoreCalculatorTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var ScoreCalculator $scoreCalculator */
        $scoreCalculator = $this->container->get(ScoreCalculator::class);

        $this->assertEquals(2.5, $scoreCalculator->match('test php sucks sd asd php sucks adaphp aaa php sucks,, php rocks done.', 'php'));
    }
}
