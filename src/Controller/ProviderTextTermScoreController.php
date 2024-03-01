<?php

namespace App\Controller;

use App\Entity\ProviderTextTerm;
use App\Service\TextTerm\TextTermFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ProviderTextTermScoreController extends AbstractController
{
    public function __construct(private readonly TextTermFetcher $textTermProvider)
    {
    }

    public function __invoke(Request $request): ProviderTextTerm
    {
        return $this->textTermProvider->provide($request->query->get('term') ?? '');
    }
}
