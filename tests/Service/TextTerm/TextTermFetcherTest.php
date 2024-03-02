<?php

namespace App\Tests\Service\TextTerm;

use App\Entity\Text;
use App\Entity\TextTerm;
use App\Service\TextTerm\TextTermFetcher;
use App\Tests\TestCase;

class TextTermFetcherTest extends TestCase
{
    public function testService(): void
    {
        $this->mockTextTermProvider();

        // fetch service
        /** @var TextTermFetcher $textTermProvider */
        $textTermProvider = $this->container->get(TextTermFetcher::class);

        // initial check
        $this->assertCount(0, $this->entityManager->getRepository(Text::class)->findAll());
        $this->assertCount(0, $this->entityManager->getRepository(TextTerm::class)->findAll());

        // test with term=php
        $textTerm = $textTermProvider->fetch('php');
        $this->assertCount(1, $texts = $this->entityManager->getRepository(Text::class)->findAll());
        $this->assertCount(1, $textTerms = $this->entityManager->getRepository(TextTerm::class)->findAll());
        $this->assertEquals('github_issue', $texts[0]->getProvider());
        $this->assertEquals('php', $textTerms[0]->getTerm());
        $this->assertEquals(1, $textTerms[0]->getText()->getId());

        // test again with term=php
        $textTerm = $textTermProvider->fetch('php');
        $this->assertCount(1, $texts = $this->entityManager->getRepository(Text::class)->findAll());
        $this->assertCount(1, $textTerms = $this->entityManager->getRepository(TextTerm::class)->findAll());
        $this->assertEquals('github_issue', $texts[0]->getProvider());
        $this->assertEquals('php', $textTerms[0]->getTerm());
        $this->assertEquals(1, $textTerms[0]->getText()->getId());

        // test again with term=js
        $textTerm = $textTermProvider->fetch('js');
        $this->assertCount(1, $texts = $this->entityManager->getRepository(Text::class)->findAll());
        $this->assertCount(2, $textTerms = $this->entityManager->getRepository(TextTerm::class)->findAll());
        $this->assertEquals('github_issue', $texts[0]->getProvider());
        $this->assertEquals('php', $textTerms[0]->getTerm());
        $this->assertEquals('js', $textTerms[1]->getTerm());
        $this->assertEquals(1, $textTerms[0]->getText()->getId());
        $this->assertEquals(1, $textTerms[1]->getText()->getId());

        // manually change provider of providerTest entity so that we can check if second providerTest will be created properly
        $texts[0]->setProvider('twitter_issue');
        $this->entityManager->persist($texts[0]);
        $this->entityManager->flush();

        $textTerm = $textTermProvider->fetch('php');
        $this->assertCount(2, $texts = $this->entityManager->getRepository(Text::class)->findAll());
        $this->assertCount(3, $textTerms = $this->entityManager->getRepository(TextTerm::class)->findAll());
        $this->assertEquals('twitter_issue', $texts[0]->getProvider());
        $this->assertEquals('github_issue', $texts[1]->getProvider());
        $this->assertEquals(1, $textTerms[0]->getText()->getId());
        $this->assertEquals(1, $textTerms[1]->getText()->getId());
        $this->assertEquals(2, $textTerms[2]->getText()->getId());

        // test again
        $textTerm = $textTermProvider->fetch('php');
        $this->assertCount(2, $texts = $this->entityManager->getRepository(Text::class)->findAll());
        $this->assertCount(3, $textTerms = $this->entityManager->getRepository(TextTerm::class)->findAll());
        $this->assertEquals('twitter_issue', $texts[0]->getProvider());
        $this->assertEquals('github_issue', $texts[1]->getProvider());
    }
}
