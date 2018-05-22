<?php

namespace App\Controller;

use App\Entity\AnswerHistory;
use App\Utils\HtmlTools;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function getAnswers(AnswerHistory $answerHistory, HtmlTools $htmlTools, Request $request)
    {
        if (!isset($_COOKIE['answers'])) {
            return $this->redirectToRoute('home');
        }

        $data = json_decode($_COOKIE['answers'], true);
        $expireDate = null;
        foreach ($data as $key => $value) {
            if ($value['id'] === $answerHistory->getId()) {
                $expireDate = $value['expireTime'];
                break;
            }
        }

        $hashData = $request->headers->get('user-agent') .
            $request->headers->get('accept-language') .
            $answerHistory->getId() .
            $expireDate;

        if (hash('md5', $hashData) !== $answerHistory->getHash()) {
            return $this->redirectToRoute('home');
        }

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
                ),
                'description' => $filterUsage->getHtml()->getShop()->getDescription(),
            ];
        }

        return new JsonResponse($urls);
    }
}
