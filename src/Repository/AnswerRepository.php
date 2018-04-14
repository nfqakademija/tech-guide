<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function getByQuestion(Question $question) : array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('answer')
            ->from('App:Answer', 'answer')
            ->where('answer.question_id = :question')
            ->setParameter('question', $question)
            ->getQuery()
            ->getResult();
    }
}
