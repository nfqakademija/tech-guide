<?php

namespace App\Utils;


use App\Entity\Answer;
use App\Entity\AnswerHistory;
use App\Entity\Category;
use App\Entity\InfluenceArea;
use App\Entity\Question;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class UserAnswers
{

    /**
     * @var Category
     */
    private $category;
    private $answerValues;
    private $questions;
    private $entityManager;

    private $currAnswerValue;
    /**
     * UserAnswers constructor.
     *
     * @param array                  $answers
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(array $answers, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->category = $entityManager
            ->getRepository(Category::class)
            ->find(array_shift($answers));
        $this->answerValues = $answers;
        $this->questions = $this->category->getQuestions();
    }

    public function saveAnswers(): void
    {
        $answerHistory = new AnswerHistory();
        $answerHistory->setCategory($this->category);

        $i = -1;
        $isDepthQuestionAsked = true;

        /**
         * @var Question $question
         */
        foreach ($this->questions as $question) {
            $i++;
            if(!$isDepthQuestionAsked) {
                $isDepthQuestionAsked = true;
                $i--;
                continue;
            }

            if($this->answerValues[$i] === -2) {
                $isDepthQuestionAsked = false;
            }

            $this->currAnswerValue =  $this->answerValues[$i];
            $answers = $question->getAnswers()->filter(function (Answer $answer) {
                return $answer->getValue() === $this->currAnswerValue;
            });

            foreach ($answers as $answer) {
                $answerHistory->addAnswer($answer);
            }
        }

        $this->entityManager->persist($answerHistory);
        $this->entityManager->flush();
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return Collection
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    /**
     * @param Collection $questions
     */
    public function setQuestions(Collection $questions): void
    {
        $this->questions = $questions;
    }
}