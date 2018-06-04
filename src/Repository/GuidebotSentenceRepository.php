<?php

namespace App\Repository;

use App\Entity\GuidebotSentence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GuidebotSentenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GuidebotSentence::class);
    }

    /**
     * @param string $purpose : available purposes: introduction, greetings, reply
     *
     * @return array
     */

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
