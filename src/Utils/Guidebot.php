<?php

namespace App\Utils;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\GuidebotSentence;
use App\Entity\Question;
use Doctrine\ORM\EntityManager;

/**
 * Guidebot is responsible for logic when retrieving the sentences from the database
 *
 * Guidebot class can generate a set of greeting messages for the user
 *
 * Example usage:
 * $guidebot = new Guidebot($entityManager)
 * foreach($guidebot->generateGreeting() as $greeting) {
 *    //do something
 * }
 *
 */
class Guidebot
{
    private $entityManager;
    private $sentence_repository;
    private $answer_repository;
    private $question_repository;
    private $category_repository;

    private $last_id = 0;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->sentence_repository = $this->entityManager
            ->getRepository(GuidebotSentence::class);
        $this->answer_repository = $this->entityManager
            ->getRepository(Answer::class);
        $this->question_repository = $this->entityManager
            ->getRepository(Question::class);
        $this->category_repository = $this->entityManager
            ->getRepository(Category::class);
    }

    /**
     * generate a set of greeting messages
     *
     * @return array of strings
     */
    public function generateGreeting() : array
    {
        $allGreetings = $this->makePriorityArray(
            $this->sentence_repository->getByPurpose('greetings'));
        $allIntroductions = $this->makePriorityArray(
            $this->sentence_repository->getByPurpose('introduction'));

        return array_merge(
            $this->pickItemsRandomly($allGreetings),
            $this->pickItemsRandomly($allIntroductions)
        );
    }

    /**
     * takes questions, answers, categories and questions from database
     * and makes them as trigger messages
     * (one message triggers another)
     *
     * @return array
     */

    public function makeTriggeringMessages()
    {
        $allMessages = array();

        foreach ($this->generateGreeting() as $greeting) {
            $allMessages['messages']['greeting'][] = array(
                'id' => $this->createUniqueId(),
                'message' => $greeting->getSentence(),
                'trigger' => $this->last_id + 1
            );
        }

        $firstQuestion = $this->question_repository->getFirst();
        $allMessages['messages']['questions'][] = array(
            'id' => $this->createUniqueId(),
            'message' => $firstQuestion->getValue(),
            'trigger' => $this->last_id + 1
        );

        $categories = $this->category_repository->getAll();
        $categoriesWithTriggers = array('id' => $this->createUniqueId());

        $offerId = $this->createUniqueId();
        $allMessages['messages']['questions'][] = array(
            'id' => $offerId,
            'message' => "Your results will be offered soon.. or, on the next sprint ;)",
            'end' => true
        );

        $i = 1;
        foreach ($categories as $category) {
            $categoriesWithTriggers['options'][] = array(
                'value'   => $i,
                'label' => $category->getCategoryName(),
                'trigger' => $this->last_id + 1
            );
            $i++;

            $questions = $category->getQuestions();
            foreach ($questions as $question) {
                $allMessages['messages']['questions'][] = array(
                    'id' => $this->createUniqueId(),
                    'message' => $question->getValue(),
                    'trigger' => $this->last_id + 1
                );

                $allMessages['messages']['options'][] =
                    $this->makeOptionsArray($offerId, $question, $questions->last());
            }
        }

        $allMessages['messages']['options'][] = $categoriesWithTriggers;

        return $allMessages;
    }

    /**
     * creates new unique id for messages
     *
     * @return int
     */
    private function createUniqueId() : int
    {
        return ++$this->last_id;
    }

    /**
     * makes an array of possible answers to a specific question
     *
     * @param int      $offerId
     * @param Question $question
     * @param Question $last
     *
     * @return array
     */
    private function makeOptionsArray(int $offerId, Question $question, Question $last) : array
    {
        $arr = array('id' => $this->createUniqueId());

        if($question === $last) {
            $trigger = $offerId;
        } else {
            $trigger = $this->last_id + 1;
        }

        $i = 1;
        foreach ($question->getAnswers() as $answer) {
            $arr['options'][] = array(
                'value'   => $i,
                'label'   => $answer->getValue(),
                'trigger' => $trigger
            );
            $i++;
        }

        return $arr;
    }

    /**
     * @param array $items that are GuidebotSentence entities
     *
     * @return array with GuidebotSentence priority attribute as key
     *               and array of GuidebotSentence entities as value
     */
    private function makePriorityArray(array $items) : array
    {
        $result = array();
        foreach ($items as $item) {
            if(!array_key_exists($item->getPriority(), $result)) {
                $result[$item->getPriority()] = array();
            }

            $result[$item->getPriority()][] = $item;
        }

        return $result;
    }

    /**
     * @param array $items that is made in makePriorityFunction
     *
     * @return array of strings
     */
    private function pickItemsRandomly(array $items) : array
    {
        $result = array();
        foreach ($items as $priority => $setOfItems) {
            $result[] = $setOfItems[
                rand(0, count($setOfItems) - 1)];

            if (rand(0, 1)) {
                break;
            }
        }

        return $result;
    }
}