<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\AnswerHistory;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function add(Category $category): void
    {
        $manager = $this->getEntityManager();

        $answerHistory = new AnswerHistory();
        $answerHistory->setCategory($category);

        $manager->persist($answerHistory);
        $manager->flush();
    }
}
