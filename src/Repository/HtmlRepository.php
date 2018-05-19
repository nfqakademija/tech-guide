<?php

namespace App\Repository;

use App\Entity\Html;
use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class HtmlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Html::class);
    }

    public function findByUrl(string $url) : ?Html
    {
        try {
            return $this->getEntityManager()
                ->getRepository('App:Html')
                ->createQueryBuilder('html')
                ->where('html.url = :url')
                ->setParameter('url', $url)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function add(Shop $shop, string $html, string $url): Html
    {
        $manager = $this->getEntityManager();

        $htmlEntity = new Html();
        $htmlEntity->setContent(addslashes($html));
        $htmlEntity->setShop($shop);
        $htmlEntity->setUrl(addslashes($url));

        $manager->persist($htmlEntity);
        $manager->flush();

        return $htmlEntity;
    }

    public function update(Html $htmlEntity, string $html): Html
    {
        $manager = $this->getEntityManager();

        $htmlEntity->setContent(addslashes($html));

        $manager->flush();

        return $htmlEntity;
    }
}
