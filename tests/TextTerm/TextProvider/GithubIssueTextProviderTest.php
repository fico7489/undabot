<?php

namespace App\Tests\TextTerm\TextProvider;

use App\Service\TextTerm\ScoreCalculator;
use App\Tests\TestCase;

class GithubIssueTextProviderTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var ScoreCalculator $scoreCalculator */
        $scoreCalculator = $this->container->get(ScoreCalculator::class);

        $this->assertEquals(1, 1);
    }
}
