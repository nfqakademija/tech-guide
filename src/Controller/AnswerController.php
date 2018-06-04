<?php

namespace App\Controller;

use App\Entity\AnswerHistory;
use App\Utils\HtmlTools;
use App\Utils\UrlBuilder;
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
     * @param AnswerHistory $answerHistory
     * @param HtmlTools     $htmlTools
     * @param Request       $request
     * @param UrlBuilder    $urlBuilder
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/answers/get/{id}", name="get_answers")
     */
    public function getAnswers(
        AnswerHistory $answerHistory,
        HtmlTools $htmlTools,
        Request $request,
        UrlBuilder $urlBuilder
    ) {
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
            $shop = $filterUsage->getHtml()->getShop();
            $urlBuilder
                ->reset()
                ->setUrl($filterUsage->getHtml()->getUrl())
                ->addFilterSeparators(
                    $shop->getFilterSeparator(),
                    $shop->getFirstFilterSeparator()
                )
                ->addFilterValueSeparators(
                    $shop->getFilterValueSeparator(),
                    $shop->getFirstFilterValueSeparator()
                );

            $articles = $htmlTools->fetchArticles($shop, $urlBuilder);
            $urls[$answerHistory->getCategory()->getCategoryName()][] = [
                'date' => $answerHistory->getAddedAt()->format('Y-m-d'),
                'url' => $urlBuilder->getUrl(),
                'name' =>  $shop->getName(),
                'logo' => $shop->getLogo(),
                'filterUsage' => $filterUsage->getValue(),
                'articles' => $articles,
                'count' => count($articles)
            ];
        }

        return new JsonResponse($urls);
    }
}
