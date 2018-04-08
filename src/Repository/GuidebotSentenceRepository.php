<?php

namespace App\Repository;

use App\Entity\GuidebotSentence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GuidebotSentence|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuidebotSentence|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuidebotSentence[]    findAll()
 * @method GuidebotSentence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuidebotSentenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GuidebotSentence::class);
    }

//    /**
//     * @return GuidebotSentence[] Returns an array of GuidebotSentence objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GuidebotSentence
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
