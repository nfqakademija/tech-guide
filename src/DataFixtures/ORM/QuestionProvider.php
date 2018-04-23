<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class QuestionProvider extends BaseProvider
{
    private $questions = [
        'What type of tech would you like to know more about?' => 1,
        'How often do you travel?' => 10,
        'How often do you post on social media?' => 11,
        'What`s the color you prefer for your device?' => 101,
        'Does price matter?' => 2
    ];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function questionValue($questionNum) : string
    {
        return array_keys($this->questions)[$questionNum - 1];
    }

    public function questionPriority($questionNum) : int
    {
        return array_values($this->questions)[$questionNum - 1];
    }
}