<?php

namespace App\Tests\TextTerm;

use App\Entity\ProviderText;
use App\Entity\ProviderTextTerm;
use App\Service\TextTerm\TextTermProvider;
use App\Tests\TestCase;

class TextTermProviderTest extends TestCase
{
    public function testService(): void
    {
        /** @var TextTermProvider $textTermProvider */
        $textTermProvider = $this->container->get(TextTermProvider::class);

        // initial check
        $this->assertCount(0, $this->entityManager->getRepository(ProviderText::class)->findAll());
        $this->assertCount(0, $this->entityManager->getRepository(ProviderTextTerm::class)->findAll());

        // test with term=php
        $textTerm = $textTermProvider->provide('php');
        $this->assertCount(1, $providerText = $this->entityManager->getRepository(ProviderText::class)->findAll());
        $this->assertCount(1, $providerTextTerm = $this->entityManager->getRepository(ProviderTextTerm::class)->findAll());
        $this->assertEquals('github_issue', $providerText[0]->getProvider());
        $this->assertEquals('php', $providerTextTerm[0]->getTerm());
        $this->assertEquals(1, $providerTextTerm[0]->getProviderText()->getId());

        // test again with term=php
        $textTerm = $textTermProvider->provide('php');
        $this->assertCount(1, $providerText = $this->entityManager->getRepository(ProviderText::class)->findAll());
        $this->assertCount(1, $providerTextTerm = $this->entityManager->getRepository(ProviderTextTerm::class)->findAll());
        $this->assertEquals('github_issue', $providerText[0]->getProvider());
        $this->assertEquals('php', $providerTextTerm[0]->getTerm());
        $this->assertEquals(1, $providerTextTerm[0]->getProviderText()->getId());

        // test again with term=js
        $textTerm = $textTermProvider->provide('js');
        $this->assertCount(1, $providerText = $this->entityManager->getRepository(ProviderText::class)->findAll());
        $this->assertCount(2, $providerTextTerm = $this->entityManager->getRepository(ProviderTextTerm::class)->findAll());
        $this->assertEquals('github_issue', $providerText[0]->getProvider());
        $this->assertEquals('php', $providerTextTerm[0]->getTerm());
        $this->assertEquals('js', $providerTextTerm[1]->getTerm());
        $this->assertEquals(1, $providerTextTerm[0]->getProviderText()->getId());
        $this->assertEquals(1, $providerTextTerm[1]->getProviderText()->getId());

        // manually change provider of providerTest entity so that we can check if second providerTest will be created properly
        $providerText[0]->setProvider('twitter_issue');
        $this->entityManager->persist($providerText[0]);
        $this->entityManager->flush();

        $textTerm = $textTermProvider->provide('php');
        $this->assertCount(2, $providerText = $this->entityManager->getRepository(ProviderText::class)->findAll());
        $this->assertCount(3, $providerTextTerm = $this->entityManager->getRepository(ProviderTextTerm::class)->findAll());
        $this->assertEquals('twitter_issue', $providerText[0]->getProvider());
        $this->assertEquals('github_issue', $providerText[1]->getProvider());
        $this->assertEquals(1, $providerTextTerm[0]->getProviderText()->getId());
        $this->assertEquals(1, $providerTextTerm[1]->getProviderText()->getId());
        $this->assertEquals(2, $providerTextTerm[2]->getProviderText()->getId());

        // test again
        $textTerm = $textTermProvider->provide('php');
        $this->assertCount(2, $providerText = $this->entityManager->getRepository(ProviderText::class)->findAll());
        $this->assertCount(3, $providerTextTerm = $this->entityManager->getRepository(ProviderTextTerm::class)->findAll());
        $this->assertEquals('twitter_issue', $providerText[0]->getProvider());
        $this->assertEquals('github_issue', $providerText[1]->getProvider());
    }
}
