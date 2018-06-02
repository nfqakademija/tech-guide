<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

class PriceFilter extends Filter
{
    private const TYPE = 'Price';

    /**
     * PriceFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds);
        $this->influenceAreas = $this->findInfluenceAreas([self::TYPE]);
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

        if ($this->influenceBounds[self::TYPE][0] === $this->influenceBounds[self::TYPE][1]) {
            return [null, []];
        }

        /**
         * @var Regex[] $regexes
         */
        $regexes = $this->retrieveRegexes($shopCategory, $this->influenceAreas[0]);

        if (\count($regexes) > 0) {
            $filterUsageCalculator->addValue(true);
            if (\count($regexes) === 1) {
                preg_match_all(
                    $regexes[0]->getContentRegex(),
                    $pageContent,
                    $matches
                );

                $value = round($matches[1][0]
                        * $this->influenceBounds[self::TYPE][0]) . $shopCategory->getShop()->getPriceSeparator()
                    . round($matches[1][0]
                        * $this->influenceBounds[self::TYPE][1]);

                return [$regexes[0]->getUrlParameter(), [$value]];
            }

            if (\count($regexes) === 2) {
                preg_match_all(
                    $regexes[0]->getContentRegex(),
                    $pageContent,
                    $min
                );
                preg_match_all(
                    $regexes[1]->getContentRegex(),
                    $pageContent,
                    $max
                );

                $minValue = round($max[1][0]
                    * $this->influenceBounds[self::TYPE][0] + $min[1][0]);
                $maxValue = round($max[1][0]
                    * $this->influenceBounds[self::TYPE][1]);

                return [
                    $regexes[0]->getUrlParameter(), [$minValue],
                    $regexes[1]->getUrlParameter(), [$maxValue]
                ];
            }
        }

        $filterUsageCalculator->addValue(
            !$this->categoryFilterExists($shopCategory->getCategory(), $this->influenceAreas[0])
        );
        return [null, []];
    }
}
