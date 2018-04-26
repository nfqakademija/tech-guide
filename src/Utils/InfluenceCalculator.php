<?php

namespace App\Utils;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;

class InfluenceCalculator
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Collection
     */
    private $questions;
    /**
     * @var array
     */
    private $answerValues;

    /**
     * InfluenceCalculator constructor.
     *
     * @param array                  $values
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(array $values, EntityManagerInterface $entityManager)
    {
        $this->category = $entityManager
            ->getRepository(Category::class)
            ->find(array_shift($values));
        $this->answerValues = $values;
        $this->questions = $this->category->getQuestions();
    }

    /**
     * Calculate min value and max value ratios for given answers
     * @return array
     */
    public function calculateInfluenceBounds() : array
    {
        $maxInfluencePoints = [];
        // change this to doctrine query (max value)
        // and use calculateInfluencePoints function
        foreach ($this->questions as $question) {
            $maxValue = 0;
            foreach ($question->getAnswers() as $answer) {
                $value = $answer->getValue();
                if($value > $maxValue) {
                    $maxValue = $value;
                }
            }

            foreach ($question->getInfluenceAreas() as $influenceArea) {
                if(!array_key_exists($influenceArea->getId(), $maxInfluencePoints)) {
                    $maxInfluencePoints[$influenceArea->getId()] = $maxValue;
                }
                else {
                    $maxInfluencePoints[$influenceArea->getId()] += $maxValue;
                }
            }
        }
        // --------------------------

        $currInfluencePoints = $this->calculateInfluencePoints($this->answerValues);
        $closestInfluencePoints = $this->calculateInfluencePoints(
            $this->calculateClosestValues($this->answerValues));
        $influenceBounds = [];
        foreach($currInfluencePoints as $key => $value) {
            $influenceBounds[$key] = [
                $closestInfluencePoints[$key] / $maxInfluencePoints[$key],
                $value / $maxInfluencePoints[$key]
            ];
        }

        return $influenceBounds;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    private function calculateInfluencePoints(array $values) : array
    {
        $influencePoints = [];
        $i = 0;
        foreach ($this->questions as $question) {
            foreach ($question->getInfluenceAreas() as $influenceArea) {
                if(!array_key_exists($influenceArea->getId(), $influencePoints)) {
                    $influencePoints[$influenceArea->getId()] = $values[$i];
                }
                else {
                    $influencePoints[$influenceArea->getId()] += $values[$i];
                }
            }
            $i++;
        }

        return $influencePoints;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    private function calculateClosestValues(array $values) : array
    {
        $closestValues = [];
        $i = 0;
        foreach ($this->questions as $question) {
            $closestValues[] = 0;
            foreach ($question->getAnswers() as $answer) {
                $answerValue = $answer->getValue();
                if($answerValue > $closestValues[$i] && $answerValue < $values[$i]) {
                    $closestValues[$i] = $answerValue;
                }
            }
            $i++;
        }
        
        return $closestValues;
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
     *
     * @return InfluenceCalculator
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
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
     *
     * @return InfluenceCalculator
     */
    public function setQuestions(Collection $questions): self
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * @return array
     */
    public function getAnswerValues(): array
    {
        return $this->answerValues;
    }

    /**
     * @param array $answerValues
     *
     * @return InfluenceCalculator
     */
    public function setAnswerValues(array $answerValues): self
    {
        $this->answerValues = $answerValues;

        return $this;
    }
    
}