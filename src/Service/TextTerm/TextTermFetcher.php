<?php

namespace App\Service\TextTerm;

use App\Entity\ProviderText;
use App\Entity\ProviderTextTerm;
use App\Service\TextTerm\Provider\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

class TextTermFetcher
{
    public function __construct(
        private readonly ScoreCalculator $scoreCalculator,
        private readonly ProviderInterface $textFetcher,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function provide(string $term): ProviderTextTerm
    {
        $provider = $this->textFetcher->getName();
        $url = $this->textFetcher->getUrl();

        $providerText = $this->getOrCreateProviderText($provider, $url);

        return $this->getOrCreateProviderTextTerm($providerText, $term);
    }

    private function getOrCreateProviderText(string $provider, string $url): ProviderText
    {
        // get text from db by url and provider, if not exists fetch and store in db
        $providerText = $this->entityManager->getRepository(ProviderText::class)->findOneBy(['provider' => $provider, 'url' => $url]);
        if (!$providerText) {
            $text = $this->textFetcher->fetchText();

            $providerText = new ProviderText();
            $providerText->setProvider($provider);
            $providerText->setUrl($url);
            $providerText->setText($text);

            $this->entityManager->persist($providerText);
            $this->entityManager->flush();
        }

        return $providerText;
    }

    private function getOrCreateProviderTextTerm(ProviderText $providerText, string $term): ProviderTextTerm
    {
        // get text_term from db by text and term, if not exists calculate and store in db
        $providerTextTerm = $this->entityManager->getRepository(ProviderTextTerm::class)->findOneBy(['providerText' => $providerText, 'term' => $term]);
        if (!$providerTextTerm) {
            $score = $this->scoreCalculator->calculate($providerText->getText(), $term);

            $providerTextTerm = new ProviderTextTerm();
            $providerTextTerm->setTerm($term);
            $providerTextTerm->setScore($score);
            $providerTextTerm->setProviderText($providerText);

            $this->entityManager->persist($providerTextTerm);
            $this->entityManager->flush();
        }

        return $providerTextTerm;
    }
}
