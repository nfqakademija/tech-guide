<?php
/**
 * Created by PhpStorm.
 * User: matas
 * Date: 4/11/18
 * Time: 5:09 PM
 */

namespace App\Utils;

use App\Entity\GuidebotSentence;
use Doctrine\ORM\EntityManager;

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