<?php

namespace App\Controller;

use App\Utils\Guidebot;
use App\Utils\Provider;
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
     * @Route("/guidebotSentences", name="guidebotSentences")
     * @Method({"GET"})
     */
    public function retrieveSentences(Guidebot $guidebot)
    {
        return new JsonResponse($guidebot->makeTriggeringMessages());
    }

    /**
     * @Route("/guidebotAnswers", name="guidebotAnswers")
     * @Method({"POST"})
     */
    public function retrieveAnswers(Request $request, EntityManagerInterface $entityManager)
    {
        $provider = new Provider(
            json_decode($request->getContent(), true)['data'],
            $entityManager
        );
        return new JsonResponse($provider->makeUrls());
    }
}
