<?php

namespace App\Repository;

use App\Entity\InfluenceArea;
use App\Entity\Regex;
use App\Entity\Shop;
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
            ->andWhere('category.id = :category')
            ->setParameters([
                'shop' => $shopCategory->getShop(),
                'category' => $shopCategory->getCategory(),
                'influenceArea' => $influenceArea,
            ])
            ->getQuery()
            ->getResult();
    }

    public function getPageContentRegex(Shop $shop) : array
    {
        return $this->getEntityManager()
            ->getRepository('App:Regex')
            ->createQueryBuilder('regex')
            ->where('regex.influenceArea is NULL')
            ->andWhere('regex.shop = :shop')
            ->setParameter('shop', $shop)
            ->getQuery()
            ->getResult();
    }

    public function getRegexesByPriority(ShopCategory $shopCategory) : array
    {
        $priceRegex  = $this->getEntityManager()
            ->getRepository('App:Regex')
            ->createQueryBuilder('regex')
            ->select('regex.urlParameter')
            ->where('regex.shop = :shop')
            ->innerJoin('regex.influenceArea', 'influenceArea')
            ->andWhere("influenceArea.content = 'Price'")
            ->innerJoin('regex.categories', 'category')
            ->andWhere('category = :category')
            ->setParameters([
                'category' => $shopCategory->getCategory(),
                'shop' => $shopCategory->getShop(),
            ])
            ->getQuery()
            ->getResult();

        $filters = $this->getEntityManager()
            ->getRepository('App:Regex')
            ->createQueryBuilder('regex')
            ->select('regex.urlParameter')
            ->where('regex.shop = :shop')
            ->innerJoin('regex.influenceArea', 'influenceArea')
            ->andWhere("influenceArea.content != 'Price'")
            ->innerJoin('regex.categories', 'category')
            ->andWhere('category = :category')
            ->innerJoin('influenceArea.questions', 'question')
            ->groupBy('regex')
            ->orderBy('MAX(question.priority)', 'DESC')
            ->setParameters([
                'category' => $shopCategory->getCategory(),
                'shop' => $shopCategory->getShop(),
            ])
            ->getQuery()
            ->getResult();

        return array_merge($priceRegex, $filters);
    }
}
