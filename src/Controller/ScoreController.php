<?php

namespace App\Controller;

use App\Service\ScoreCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    #[Route('/score', name: 'app.score')]
    public function score(ScoreCalculator $scoreCalculator): JsonResponse
    {
        $score = $scoreCalculator->match('test php sucks sd asd php sucks adaphp aaa php sucks,, php rocks done.', 'php');

        $term = 'php';

        return new JsonResponse([
            'term' => $term,
            'score' => $score,
        ]);
    }
}
