<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\ProviderTextTermScoreController;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/provider_text_terms/score',
            controller: ProviderTextTermScoreController::class,
            read: false,
        ),
    ],
)]
#[ORM\Entity]
class ProviderTextTerm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private ?string $term = null;

    #[ORM\Column(type: 'float')]
    private ?float $score = null;

    #[ORM\ManyToOne(targetEntity: ProviderText::class, inversedBy: 'providerTextTerms')]
    private ProviderText $providerText;

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

    public function getProviderText(): ProviderText
    {
        return $this->providerText;
    }

    public function setProviderText(ProviderText $providerText): void
    {
        $this->providerText = $providerText;
    }
}
