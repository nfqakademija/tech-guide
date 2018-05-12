<?php

namespace App\Utils\Filters;

use App\Entity\InfluenceArea;
use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;

abstract class Filter
{
    /**
     * @var InfluenceArea[] $influenceAreas
     */
    protected $influenceAreas;
    protected $influenceBounds;

    private $regexRepository;
    private $influenceAreaRepository;

    /**
     * Filter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        $this->influenceAreaRepository = $entityManager
            ->getRepository(InfluenceArea::class);
        $this->regexRepository = $entityManager
            ->getRepository(Regex::class);

        $this->influenceBounds = $influenceBounds;
    }


    /**
     * @param array $contents
     *
     * @return array
     */
    protected function findInfluenceAreas(array $contents) : array
    {
        $influenceAreas = [[]];
        foreach ($contents as $content) {
            $influenceAreas[] = $this->influenceAreaRepository->findBy(['content' => $content]);
        }

        return array_merge(...$influenceAreas);
    }


    /**
     * @param ShopCategory  $shopCategory
     * @param InfluenceArea $influenceArea
     *
     * @return array
     */
    protected function retrieveRegexes(ShopCategory $shopCategory, InfluenceArea $influenceArea) : array
    {
        return $this->regexRepository->getRegexesForFilter($shopCategory, $influenceArea);
    }

    /**
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    abstract public function filter(string $pageContent, ShopCategory $shopCategory) : array;
}
