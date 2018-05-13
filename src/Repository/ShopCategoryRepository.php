<?php

namespace App\Repository;

use App\Entity\ShopCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ShopCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShopCategory::class);
    }

    public function updateHtmlCode(ShopCategory $shopCategory, string $html): void
    {
        $manager = $this->getEntityManager();

        $shopCategory->setHtml(addslashes($html));
        $shopCategory->setHtmlAddedAt(new \DateTime('now'));

        $manager->flush();
    }
}
