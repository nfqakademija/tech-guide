<?php

namespace App\Repository;

use App\Entity\FilterUsage;
use App\Entity\Html;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FilterUsageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FilterUsage::class);
    }

    public function add(Html $html, int $value) : FilterUsage
    {
        $manager = $this->getEntityManager();

        $filterUsage = new FilterUsage();
        $filterUsage->setHtml($html);
        $filterUsage->setValue($value);

        $manager->persist($filterUsage);
        $manager->flush();

        return $filterUsage;
    }
}
