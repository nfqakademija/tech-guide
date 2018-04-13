<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new AppNativeLoader();
        $objectSet = $loader->loadFiles([
            __DIR__ . '/question_fixtures.yaml',
            __DIR__ . '/answer_fixtures.yaml'
        ])->getObjects();

        foreach($objectSet as $object)
        {
            $manager->persist($object);
        }

        $manager->flush();
    }
}