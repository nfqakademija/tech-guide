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
        'What`s the color you prefer for your device then?' => 101,
        'Does price matter?' => 2,
        'Do you often try to complete all tasks at once?' => 10,
        'How often do you play video games?' => 11,
        'Do you like downloading various content?' => 12,
        'So you say that you`d love to have cinema in your home?' => 11,
        'And for the last question.. How much space do you have for your TV?' => 12
    ];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function questionContent($questionNum) : string
    {
        return array_keys($this->questions)[$questionNum - 1];
    }

    public function questionPriority($questionNum) : int
    {
        return array_values($this->questions)[$questionNum - 1];
    }
}