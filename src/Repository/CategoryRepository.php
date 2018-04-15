<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getAll() : array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('category')
            ->from('App:Category', 'category')
            ->getQuery()
            ->getResult();
    }
}
