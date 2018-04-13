<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class QuestionProvider extends BaseProvider
{
    private $questions = array(
        'What type of tech would you like to know more about?' => 1,
        'How often do you travel?' => 10,
        'How often do you post on social media' => 11,
        'What`s the color you prefer for your device?' => 101
    );

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function question_value($question_num)
    {
        return array_keys($this->questions)[$question_num - 1];
    }

    public function question_priority($question_num)
    {
        return array_values($this->questions)[$question_num - 1];
    }
}