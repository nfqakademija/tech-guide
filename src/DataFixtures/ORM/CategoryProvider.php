<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class CategoryProvider extends BaseProvider
{
    private $categories = [
        'Smartphones',
        'Smart watches',
        'Laptops'
    ];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function categoryValue($catNum) : string
    {
        return $this->categories[$catNum - 1];
    }
}