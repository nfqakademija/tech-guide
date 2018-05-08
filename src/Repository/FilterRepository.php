<?php

namespace App\Repository;

use App\Entity\Filter;
use App\Entity\InfluenceArea;
use App\Entity\ShopCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FilterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Filter::class);
    }

    public function getShopCategoryFiltersByInfluenceArea(
        ShopCategory $shopCategory,
        InfluenceArea $influenceArea
    ) : array {
        return $this->getEntityManager()
            ->getRepository('App:Filter')
            ->createQueryBuilder('filter')
            ->where('filter.influenceArea = :influenceArea')
            ->innerJoin('filter.shops', 'shop')
            ->andWhere('shop.id = :shop_id')
            ->innerJoin('filter.regexes', 'regex')
            ->innerJoin('regex.categories', 'category')
            ->andWhere('category.id = :category_id')
            ->setParameters([
                'shop_id' => $shopCategory->getShop(),
                'category_id' => $shopCategory->getCategory(),
                'influenceArea' => $influenceArea,
            ])
            ->getQuery()
            ->getResult();
    }
}
