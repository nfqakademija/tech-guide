<?php

namespace App\Repository;

use App\Entity\GuidebotSentence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GuidebotSentenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GuidebotSentence::class);
    }

    public function getByPurpose(string $purpose) : array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('sentence')
            ->from('App:GuidebotSentence', 'sentence')
            ->where('sentence.purpose = :purpose')
            ->setParameter('purpose', $purpose)
            ->orderBy('sentence.priority')
            ->getQuery()
            ->getResult();
    }
}
