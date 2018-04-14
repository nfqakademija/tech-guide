<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class AnswerProvider extends BaseProvider
{
    private $answers = array(
        'Mobile device' => 1,
        'Smartwatch' => 1,
        'Laptop' => 1,
        'I don`t like travelling' => 2,
        'I`m always on the road!' => 2,
        'Only a few times a year' => 2,
        'I don`t - it`s a waste of time' => 3,
        'Every other day!' => 3,
        'Few times in a month' => 3,
        'Black' => 4,
        'White' => 4,
        'Gold' => 4,
        'Silver' => 4
    );

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function answer_value($answer_num) : string
    {
        return array_keys($this->answers)[$answer_num - 1];
    }

    public function question_for_answer($answer_num) : int
    {
        return array_values($this->answers)[$answer_num - 1];
    }
}