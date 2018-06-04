<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

class SizeFilter extends Filter
{
    /**
     * SizeFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds, 'Size');
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

        if (\count($regexes) > 0) {
            $filterUsageCalculator->addValue(true);
            $pageContent = $this->reduceHtml($regexes[0], $pageContent);
            if ($pageContent !== null) {
                return $this->formatResults(
                    $regexes[0], $this->fetchFilterValues($regexes[0]->getContentRegex(), $pageContent));
            }
        }

        $filterUsageCalculator->addValue(
            !$this->categoryFilterExists($shopCategory->getCategory(), $this->influenceArea)
        );

        return [null, []];
    }
}
