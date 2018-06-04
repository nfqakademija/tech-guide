<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

class CameraFilter extends Filter
{
    /**
     * PriceFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds, 'Camera');
    }


    /**
     * @param string                $pageContent
     * @param ShopCategory          $shopCategory
     * @param FilterUsageCalculator $filterUsageCalculator
     *
     * @return array
     */
    public function filter(
        string $pageContent,
        ShopCategory $shopCategory,
        FilterUsageCalculator $filterUsageCalculator
    ) : array {
        /**
         * @var Regex[] $regexes
         */
        $regexes = $this->retrieveRegexes($shopCategory, $this->influenceArea);
        
        $cameraFilters = [];
        foreach ($regexes as $regex) {
            $pageContent = $this->reduceHtml($regex, $pageContent);
            $filterUsageCalculator->addValue(true);
            if ($pageContent !== null) {
                $cameraFilters = array_merge(
                    $cameraFilters,
                    $this->formatResults($regex, $this->fetchFilterValues($regex->getContentRegex(), $pageContent)));
            }
        }

        for ($i = 0; $i < 2 - \count($regexes); $i++) {
            $filterUsageCalculator->addValue(
                !$this->categoryFilterExists($shopCategory->getCategory(),
                    $this->influenceArea)
            );
        }

        return $cameraFilters;
    }
}
