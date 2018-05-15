<?php

namespace App\Controller;

use App\Utils\Guidebot;
use App\Utils\Provider;
use App\Utils\UserAnswers;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuidebotController extends Controller
{
    /**
     * @Route("/guidebot", name="guidebot")
     */
    public function index()
    {
        return $this->render('guidebot/index.html.twig', [
            'controller_name' => 'GuidebotController',
        ]);
    }

    /**
     * @Route("/api/guidebotSentences", name="guidebotSentences")
     * @Method({"GET"})
     */
    public function retrieveSentences(Guidebot $guidebot)
    {
        return new JsonResponse($guidebot->makeTriggeringMessages());
    }

    /**
     * @Route("/api/guidebotOffer", name="guidebotOffer")
     * @Method({"POST"})
     */
    public function retrieveAnswers(Request $request, EntityManagerInterface $entityManager)
    {
        $answers = array_map('\intval', json_decode($request->getContent(), true)['data']);

        $userAnswers = new UserAnswers($answers, $entityManager);
        $userAnswersId = $userAnswers->saveAnswers();

        $date = (new \DateTime('+3 months'))->format(\DateTime::COOKIE);
        if(!isset($_COOKIE['answers'])) {
            setcookie('answers', json_encode([]), strtotime($date));
        }

        $data = json_decode($_COOKIE['answers'], true);
        $data[] = ['id' => $userAnswersId, 'expireTime' => $date];
        setcookie('answers', json_encode($data), strtotime($date));

        $provider = new Provider($answers, $entityManager);

        return new JsonResponse($provider->makeUrls());
    }
}
