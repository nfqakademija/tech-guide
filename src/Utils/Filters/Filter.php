<?php

namespace App\Utils\Filters;

use App\Entity\Category;
use App\Entity\InfluenceArea;
use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Repository\InfluenceAreaRepository;
use App\Repository\RegexRepository;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

abstract class Filter
{
    /**
     * @var InfluenceArea[] $influenceAreas
     */
    protected $influenceAreas;
    protected $influenceBounds;

    /**
     * @var RegexRepository $regexRepository
     */
    private $regexRepository;
    /**
     * @var InfluenceAreaRepository $influenceAreaRepository
     */
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
     * @param Category      $category
     * @param InfluenceArea $influenceArea
     *
     * @return bool
     */
    protected function categoryFilterExists(Category $category, InfluenceArea $influenceArea) : bool
    {
        return $this->influenceAreaRepository->getInfluenceAreaCountByCategory($category, $influenceArea) > 0;
    }

    /**
     * @param string                $pageContent
     * @param ShopCategory          $shopCategory
     * @param FilterUsageCalculator $filterUsageCalculator
     *
     * @return array
     */
    abstract public function filter(string $pageContent, ShopCategory $shopCategory, FilterUsageCalculator $filterUsageCalculator) : array;
}
