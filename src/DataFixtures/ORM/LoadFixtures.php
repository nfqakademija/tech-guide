<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager) : void
    {
        $loader = new AppNativeLoader();
        $objectSet = $loader->loadFiles([
            __DIR__ . '/guidebot_fixtures.yaml',
            __DIR__ . '/question_fixtures.yaml',
            __DIR__ . '/answer_fixtures.yaml',
            __DIR__ . '/category_fixtures.yaml',
            __DIR__ . '/shop_fixtures.yaml',
            __DIR__ . '/influenceArea_fixtures.yaml'
        ])->getObjects();

        foreach ($objectSet as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }
}
