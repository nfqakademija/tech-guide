<?php

namespace App\Utils\Filters;

use App\Entity\Category;
use App\Entity\InfluenceArea;
use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Repository\InfluenceAreaRepository;
use App\Repository\RegexRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class Filter
{
    protected $influenceArea;
    protected $influenceBounds;
    protected $type;
    protected $isUsed;

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
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds, string $type)
    {
        $this->influenceAreaRepository = $entityManager
            ->getRepository(InfluenceArea::class);
        $this->regexRepository = $entityManager
            ->getRepository(Regex::class);

        $this->influenceBounds = $influenceBounds;
        $this->type = $type;
        $this->influenceArea = $this->findInfluenceArea($type);
    }

    /**
     * @param string $content
     *
     * @return InfluenceArea
     */
    protected function findInfluenceArea(string $content) : InfluenceArea
    {
        return $this->influenceAreaRepository->findBy(['content' => $content])[0];
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
     * @param Regex $regex
     * @param array $data
     *
     * @return array
     */
    protected function formatResults(Regex $regex, array $data) : array
    {
        asort($data);
        $count = \count($data);
        $this->isUsed = true;

        return [
            $regex->getUrlParameter(),
            array_keys(\array_slice(
                $data,
                round($this->influenceBounds[$this->type][0] * $count),
                round($this->influenceBounds[$this->type][1] * $count),
                true
            ))
        ];
    }

    protected function reduceHtml(Regex $regex, $pageContent) : ?string
    {
        preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);

        if (isset($match[1])) {
            return $match[1];
        }

        return null;
    }

    /**
     * @param string $regex
     * @param string $pageContent
     * @param float  $multiply
     *
     * @return array
     */
    protected function fetchFilterValues(string $regex, string $pageContent, float $multiply = 1) : array
    {
        $filtersAndValues = [];
        preg_match_all($regex, $pageContent, $matches);
        for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
            $filtersAndValues[$matches[1][$i]] = $matches[2][$i] * $multiply;
        }

        return $filtersAndValues;
    }

    protected function checkUsage(Category $category) : void
    {
        $this->checkInfluenceAreaUsage($category, $this->influenceArea);
    }

    protected function checkInfluenceAreaUsage(Category $category, InfluenceArea $influenceArea) : void
    {
        if ($this->influenceAreaRepository->getInfluenceAreaCountByCategory($category, $influenceArea) > 0) {
            $this->isUsed = false;
        }
    }

    /**
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    abstract public function filter(string $pageContent, ShopCategory $shopCategory) : array;

    /**
     * @return bool|null
     */
    public function isUsed(): ?bool
    {
        return $this->isUsed;
    }

    /**
     * @param bool $isUsed
     *
     * @return Filter
     */
    public function setIsUsed(bool $isUsed): self
    {
        $this->isUsed = $isUsed;

        return $this;
    }
}
