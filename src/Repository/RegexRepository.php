<?php

namespace App\Repository;

use App\Entity\Filter;
use App\Entity\Regex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RegexRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Regex::class);
    }

    public function getRegexesByFilter(Filter $filter) : array
    {
        return $this->getEntityManager()
            ->getRepository('App:Regex')
            ->createQueryBuilder('regex')
            ->where('regex.filter = :filter')
            ->setParameter('filter', $filter)
            ->getQuery()
            ->getResult();
    }
}
