<?php

namespace App\Controller;

use App\Utils\Guidebot;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     */
    public function retrieveSentences(Guidebot $guidebot)
    {
        return new JsonResponse($guidebot->makeTriggeringMessages());
    }
}
