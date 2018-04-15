<?php

namespace App\DataFixtures\ORM;

use Faker\Provider\Base as BaseProvider;
use Faker\Generator;

class CategoryProvider extends BaseProvider
{
    private $categories = array(
        'Smartphones',
        'Smart watches',
        'Laptops'
    );

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function category_value($cat_num) : string
    {
        return $this->categories[$cat_num - 1];
    }
}