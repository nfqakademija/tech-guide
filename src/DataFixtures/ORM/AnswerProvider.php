<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class AnswerProvider extends BaseProvider
{
    private $currQuestion = 2;
    private $currValue = -1;
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
        'Not really, I guess..' => 5,
        'The cheaper the better!' => 5,
        'I think that an average price should work' => 5,
        'Luxury is what I strive for!' => 5,
        'One task at time!' => 6,
        'Sometimes, I suppose..' => 6,
        'There`s so much work to do that I just can`t have it other way!' => 6,
        'Never. There are better things to do' => 7,
        'I find it fun to play casual games sometimes' => 7,
        'I sometimes play some of the newest games that are around' => 7,
        'Whenever I can!' => 7,
        'Nah. Why should I download something when it`s available online?' => 8,
        'Only when I need to' => 8,
        'Movies, games, pictures, music.. Everything is being floaded to my computer!' => 8,
        'Not really, actually..' => 9,
        'Hmm.. Well, I never thought about it really' => 9,
        'That`d be astonishing!' => 9,
        'Little to none, unfortunately..' => 10,
        'Just about enough for a decent TV' => 10,
        'Plenty of it!' => 10,
        'Of course it is!' => 11,
        'No, not really actually' => 11,
        'It takes a big part of my life!' => 12,
        'If I think about, it ain`t that important..' => 12,
    ];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function answerContent($answerNum) : string
    {
        return array_keys($this->answers)[$answerNum - 1];
    }

    public function questionForAnswer($answerNum) : int
    {
        return array_values($this->answers)[$answerNum - 1];
    }

    public function calculateValue($answerNum) : int
    {
        if ($this->questionForAnswer($answerNum) === $this->currQuestion) {
            $this->currValue++;
            return $this->currValue;
        }

        return $this->evaluateCurrentValue($this->questionForAnswer($answerNum) === 5 ? 0 : 1, $answerNum);
    }
    
    public function calculateFollowUpValue($answerNum)
    {
        if ($this->questionForAnswer($answerNum) === $this->currQuestion) {
            $this->currValue--;
            return $this->currValue;
        }
        
        return $this->evaluateCurrentValue(-1, $answerNum);
    }
    
    private function evaluateCurrentValue($value, $answerNum)
    {
        $this->currValue = $value;
        $this->currQuestion = $this->questionForAnswer($answerNum);
        return $this->currValue;
    }
}
