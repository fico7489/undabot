<?php

namespace App\Controller;

use App\Entity\TextTerm;
use App\Service\TextTerm\TextTermFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class TextTermScoreController extends AbstractController
{
    public function __construct(private readonly TextTermFetcher $textTermFetcher)
    {
    }

    public function __invoke(Request $request): TextTerm
    {
        return $this->textTermFetcher->fetch($request->query->get('term', ''));
    }
}
