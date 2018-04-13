<?php

namespace App\Utils;

use App\Entity\GuidebotSentence;
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

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->sentence_repository = $this->entityManager
            ->getRepository(GuidebotSentence::class);
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
                rand(0, count($setOfItems) - 1)]->getSentence();

            if (rand(0, 1)) {
                break;
            }
        }

        return $result;
    }
}