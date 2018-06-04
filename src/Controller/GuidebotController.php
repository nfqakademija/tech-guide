<?php

namespace App\Controller;

use App\Entity\FilterUsage;
use App\Entity\Html;
use App\Repository\HtmlRepository;
use App\Utils\Guidebot;
use App\Utils\Provider;
use App\Utils\UserAnswers;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuidebotController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/guidebot", name="guidebot")
     */
    public function index()
    {
        return $this->render('guidebot/index.html.twig', [
            'controller_name' => 'GuidebotController',
        ]);
    }

    /**
     * @param string           $apiKey
     * @param Guidebot         $guidebot
     * @param SessionInterface $session
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/api/guidebotSentences/{apiKey}", name="guidebotSentences")
     * @Method({"GET"})
     */
    public function retrieveSentences(string $apiKey, Guidebot $guidebot, SessionInterface $session)
    {
        if ($apiKey === $session->get('api_key')) {
            return new JsonResponse($guidebot->makeTriggeringMessages());
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @param string                 $apiKey
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param SessionInterface       $session
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     *
     * @Route("/api/guidebotOffer/{apiKey}", name="guidebotOffer")
     * @Method({"POST"})
     */
    public function retrieveAnswers(
        string $apiKey,
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ) {
        if ($apiKey !== $session->get('api_key')) {
            return $this->redirectToRoute('home');
        }

        $answers = array_map('\intval', json_decode($request->getContent(), true)['data']);

        $provider = new Provider($answers, $entityManager);
        $urls = $provider->makeUrls();

        $userAnswers = new UserAnswers($answers, $entityManager);
        $answerHistory = $userAnswers->saveAnswers();

        /**
         * @var HtmlRepository $htmlRepository
         */
        $htmlRepository = $entityManager->getRepository(Html::class);
        foreach ($urls as $url) {
            $filterUsage = new FilterUsage();
            $filterUsage
                ->setValue($url['filterUsage'])
                ->setHtml($htmlRepository->findByUrl($url['url']));
            $answerHistory->addFilterUsage($filterUsage);
        }

        $date = (new \DateTime('+3 months'))->format(\DateTime::COOKIE);
        if (!isset($_COOKIE['answers'])) {
            $_COOKIE['answers'] = json_encode([]);
        }

        $data = json_decode($_COOKIE['answers'], true);
        $data[] = ['id' => $answerHistory->getId(), 'expireTime' => $date];
        setcookie('answers', json_encode($data), strtotime($date), '/');

        $hashData = $request->headers->get('user-agent') .
            $request->headers->get('accept-language') .
            $answerHistory->getId() .
            $date;
        $answerHistory->setHash(hash('md5', $hashData));
        $entityManager->flush();

        return new JsonResponse($urls);
    }
}
