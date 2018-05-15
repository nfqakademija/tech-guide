<?php

namespace App\Controller;

use App\Entity\AnswerHistory;
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
    public function getAnswers(AnswerHistory $answerHistory)
    {
        return $this->render('answer/answers.html.twig', [
            'answerHistory' => $answerHistory
        ]);
    }
}
