<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new Get(),
    ],
)]
#[ORM\Entity]
class Text
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private ?string $provider = null;

    #[ORM\Column(type: 'string')]
    private ?string $url = null;

    #[ORM\Column(type: 'text')]
    private ?string $text = null;

    #[ORM\OneToMany(targetEntity: TextTerm::class, mappedBy: 'text')]
    private Collection $textTerms;

    public function __construct()
    {
        $this->textTerms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): void
    {
        $this->provider = $provider;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getTextTerms(): Collection
    {
        return $this->textTerms;
    }

    public function setTextTerms(Collection $textTerms): void
    {
        $this->textTerms = $textTerms;
    }
}
