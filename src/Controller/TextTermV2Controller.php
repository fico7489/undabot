<?php

namespace App\Controller;

use App\Entity\TextTerm;
use App\Service\TextTerm\TextTermFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class TextTermV2Controller extends AbstractController
{
    public function __construct(private readonly TextTermFetcher $textTermFetcher)
    {
    }

    public function __invoke(Request $request, TextTerm $textTerm): TextTerm
    {
        return $this->textTermFetcher->fetch($textTerm->getTerm());
    }
}
