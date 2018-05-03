<?php

namespace App\DataFixtures\ORM;

use Nelmio\Alice\Loader\NativeLoader;
use Faker\Generator as FakerGenerator;

class AppNativeLoader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = parent::createFakerGenerator();
        $generator->addProvider(new GuidebotSentenceProvider($generator));
        $generator->addProvider(new QuestionProvider($generator));
        $generator->addProvider(new AnswerProvider($generator));
        return $generator;
    }
}
