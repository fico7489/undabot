<?php

namespace App\Service\TextTerm;

use App\Entity\Text;
use App\Entity\TextTerm;
use App\Service\TextTerm\Provider\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

class TextTermFetcher
{
    public function __construct(
        private readonly ScoreCalculator $scoreCalculator,
        private readonly ProviderInterface $provider,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function fetch(string $term): TextTerm
    {
        $provider = $this->provider->getName();
        $url = $this->provider->getUrl();

        $text = $this->getOrCreateText($provider, $url);

        return $this->getOrCreateTextTerm($text, $term);
    }

    private function getOrCreateText(string $provider, string $url): Text
    {
        // get text from db by url and provider, if not exists fetch and store in db
        $text = $this->entityManager->getRepository(Text::class)->findOneBy(['provider' => $provider, 'url' => $url]);
        if (!$text) {
            $providerText = $this->provider->fetchProviderText();

            $text = new Text();
            $text->setProvider($provider);
            $text->setUrl($url);
            $text->setText($providerText);

            $this->entityManager->persist($text);
            $this->entityManager->flush();
        }

        return $text;
    }

    private function getOrCreateTextTerm(Text $text, string $term): TextTerm
    {
        // get text_term from db by text and term, if not exists calculate and store in db
        $textTerm = $this->entityManager->getRepository(TextTerm::class)->findOneBy(['text' => $text, 'term' => $term]);
        if (!$textTerm) {
            $score = $this->scoreCalculator->calculate($text->getText(), $term);

            $textTerm = new TextTerm();
            $textTerm->setTerm($term);
            $textTerm->setScore($score);
            $textTerm->setText($text);
            $textTerm->setIsNew(true);

            $this->entityManager->persist($textTerm);
            $this->entityManager->flush();
        }

        return $textTerm;
    }
}
