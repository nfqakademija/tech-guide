<?php

namespace App\Controller;

use App\Entity\AnswerHistory;
use App\Utils\HtmlTools;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnswerController extends Controller
{
    /**
     * @Route("/answer", name="answer")
     */
    public function index()
    {
        return $this->render('answer/index.html.twig', [
            'controller_name' => 'AnswerController',
        ]);
    }

    /**
     * @Route("/answers/get/{id}", name="get_answers")
     */
    public function getAnswers(AnswerHistory $answerHistory, HtmlTools $htmlTools)
    {
        $urls = [];
        foreach ($answerHistory->getFilterUsages() as $filterUsage) {
            $urls[$answerHistory->getCategory()->getCategoryName()][] = [
                'date' => $answerHistory->getAddedAt()->format('Y-m-d'),
                'url' => $filterUsage->getHtml()->getUrl(),
                'logo' => $filterUsage->getHtml()->getShop()->getLogo(),
                'filterUsage' => $filterUsage->getValue(),
                'count' => $htmlTools->getUrlCount(
                    $filterUsage->getHtml()->getShop(),
                    $filterUsage->getHtml()->getUrl()
                )
            ];
        }

        return new JsonResponse($urls);
    }
}
