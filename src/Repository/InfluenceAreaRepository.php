<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\InfluenceArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InfluenceAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InfluenceArea::class);
    }

    public function getInfluenceAreaCountByCategory(Category $category, InfluenceArea $influenceArea) : int
    {
        return $this->getEntityManager()
            ->getRepository('App:InfluenceArea')
            ->createQueryBuilder('influenceArea')
            ->select('count(influenceArea.id)')
            ->where('influenceArea = :influenceArea')
            ->innerJoin('influenceArea.regexes', 'regex')
            ->innerJoin('regex.categories', 'category')
            ->andWhere('category = :category')
            ->setParameters([
                'category' => $category,
                'influenceArea' => $influenceArea,
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }
}
