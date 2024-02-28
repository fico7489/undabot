<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new Get(),
    ],
)]
#[ORM\Entity]
class WordScore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $term = null;

    #[ORM\Column(type: 'float', nullable: false)]
    private ?string $score = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
