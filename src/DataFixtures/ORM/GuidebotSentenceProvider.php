<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class GuidebotSentenceProvider extends BaseProvider
{
    private $noOfGreetings = 10;

    private $greetings = [
        'Hello!' => 1,
        'Hey!' => 1,
        'Hi!' => 1,
        'What`s up?' => 2,
        'Nice to see you here.' => 2,
        'Good to see you.' => 2,
        'How`s your day?' => 3,
        'How`s it going?' => 3,
        'How are you doing?' => 3,
        'What`s new?' => 3,
        'I hope you are having a great day.' => 3
    ];

    private $introductions = [
        'I`m Guidebot - your personal tech assistant.' => 1,
        'I`m Guidebot - a technology advisor.' => 1,
        'I`ll be helping you pick your desired technology.' => 2,
        'I`ll be helping you with technology today.' => 2,
        'I`m here to help you.' => 2
    ];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function introductionValue($introductionNum) : string
    {
        return array_keys($this->introductions)[$introductionNum - $this->noOfGreetings];
    }

    public function introductionPriority($introductionNum) : int
    {
        return array_values($this->introductions)[$introductionNum - $this->noOfGreetings];
    }

    public function greetingsValue($greetingNum) : string
    {
        return array_keys($this->greetings)[$greetingNum - 1];
    }

    public function greetingsPriority($greetingNum) : int
    {
        return array_values($this->greetings)[$greetingNum - 1];
    }
}
