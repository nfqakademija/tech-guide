<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class AnswerProvider extends BaseProvider
{
    private $currQuestion = 2;
    private $currValue = 0;
    private $answers = [
        'I don`t like travelling' => 2,
        'Only a few times a year' => 2,
        'I`m always on the road!' => 2,
        'I don`t - it`s a waste of time' => 3,
        'Few times in a month' => 3,
        'Every other day!' => 3,
        'Black' => 4,
        'White' => 4,
        'Gold' => 4,
        'Silver' => 4,
        'I want a cheap phone' => 5,
        'Not really, I guess..' => 5,
        'Luxury is what I strive for!' => 5,
    ];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function answerValue($answerNum) : string
    {
        return array_keys($this->answers)[$answerNum - 1];
    }

    public function questionForAnswer($answerNum) : int
    {
        return array_values($this->answers)[$answerNum - 1];
    }

    public function calculateValue($answerNum) : int
    {
        if($this->questionForAnswer($answerNum) === $this->currQuestion) {
            $this->currValue++;
            return $this->currValue;
        }

        $this->currValue = 1;
        $this->currQuestion = $this->questionForAnswer($answerNum);
        return $this->currValue;
    }

}