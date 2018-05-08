<?php

namespace App\Utils\Filters;

use App\Entity\InfluenceArea;
use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Entity\Filter as EntityFilter;
use App\Utils\InfluenceCalculator;
use Doctrine\ORM\EntityManagerInterface;

abstract class Filter
{
    /**
     * @var InfluenceArea[] $influenceAreas
     */
    protected $influenceAreas;
    protected $influenceBounds;

    private $filterRepository;
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
        $this->filterRepository = $entityManager
            ->getRepository(EntityFilter::class);
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
     * @param EntityFilter $filter
     *
     * @return array
     */
    protected function findRegexes(EntityFilter $filter) : array
    {
        return $this->regexRepository->getRegexesByFilter($filter);
    }

    /**
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    protected function retrieveFilters(ShopCategory $shopCategory) : array
    {
        $filters = [[]];
        foreach ($this->influenceAreas as $influenceArea) {
            $filters[] = $this->filterRepository->getShopCategoryFiltersByInfluenceArea(
                $shopCategory,
                $influenceArea
            );
        }

        return array_merge(...$filters);
    }

    /**
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    abstract public function filter(string $pageContent, ShopCategory $shopCategory) : array;
}
