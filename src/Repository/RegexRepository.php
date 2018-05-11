<?php

namespace App\Repository;

use App\Entity\InfluenceArea;
use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RegexRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Regex::class);
    }

    public function getRegexesForFilter(ShopCategory $shopCategory, InfluenceArea $influenceArea) : array
    {
        return $this->getEntityManager()
            ->getRepository('App:Regex')
            ->createQueryBuilder('regex')
            ->where('regex.influenceArea = :influenceArea')
            ->andWhere('regex.shop = :shop')
            ->innerJoin('regex.categories', 'category')
            ->andWhere('category.id = :category' )
            ->setParameters([
                'shop' => $shopCategory->getShop(),
                'category' => $shopCategory->getCategory(),
                'influenceArea' => $influenceArea,
            ])
            ->getQuery()
            ->getResult();
    }
}
