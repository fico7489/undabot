<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\TextTermScoreController;
use App\Controller\TextTermScoreV2Controller;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/text_terms/score',
            controller: TextTermScoreController::class,
            read: false,
        ),
        new Get(
            uriTemplate: '/v2/text_terms/score',
            controller: TextTermScoreV2Controller::class,
            read: false,
        ),
    ],
)]
#[ORM\Entity]
class TextTerm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private ?string $term = null;

    #[ORM\Column(type: 'float')]
    private ?float $score = null;

    #[ORM\ManyToOne(targetEntity: Text::class, inversedBy: 'textTerms')]
    private Text $text;

    private bool $isNew = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(?string $term): void
    {
        $this->term = $term;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function setScore(float $score): void
    {
        $this->score = $score;
    }

    public function getText(): Text
    {
        return $this->text;
    }

    public function setText(Text $text): void
    {
        $this->text = $text;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }

    public function setIsNew(bool $isNew): void
    {
        $this->isNew = $isNew;
    }
}
