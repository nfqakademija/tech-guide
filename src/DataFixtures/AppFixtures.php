<?php

namespace App\DataFixtures;

use App\Entity\GuidebotSentence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->greetingFixtures($manager);
        $this->introductionFixtures($manager);
    }

    private function greetingFixtures(ObjectManager $manager) : void
    {
        $greetings = array(
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
        );

        $this->addGuidebotSentences($greetings, 'greetings', $manager);
    }

    private function introductionFixtures(ObjectManager $manager) : void
    {
        $introduction = array(
            'I`m Guidebot - your personal tech assistant.' => 1,
            'I`m Guidebot - a technology advisor.' => 1,
            'I`ll be helping you pick your desired technology.' => 2,
            'I`ll be helping you with technology today.' => 2,
            'I`m here to help you.' => 2
        );

        $this->addGuidebotSentences($introduction, 'introduction', $manager);
    }

    private function addGuidebotSentences(array $sentences, string $purpose, ObjectManager $manager) : void
    {
        foreach ($sentences as $sentence => $priority) {
            $guidebotSentence = new GuidebotSentence();
            $guidebotSentence->setPriority($priority);
            $guidebotSentence->setSentence($sentence);
            $guidebotSentence->setPurpose($purpose);
            $manager->persist($guidebotSentence);
        }

        $manager->flush();
    }

}