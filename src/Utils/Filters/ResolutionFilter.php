<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

class ResolutionFilter extends Filter
{
    /**
     * ResolutionFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds, 'Resolution');
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

        if (isset($this->influenceBounds[$this->type][0])) {
            if (\count($regexes) > 0) {
                $filterUsageCalculator->addValue(true);

                $pageContent = $this->reduceHtml($regexes[0], $pageContent);
                if ($pageContent !== null) {
                    $resolutionAndValues = [];

                    preg_match_all($regexes[0]->getContentRegex(), $pageContent, $matches);
                    for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                        $resolutionAndValues[$matches[1][$i]] = $matches[2][$i] * $matches[3][$i];
                    }

                    $this->fetchFilterValues($regexes[0]->getContentRegex(), $pageContent);
                    return $this->formatResults($regexes[0], $resolutionAndValues);
                }
            }

            $filterUsageCalculator->addValue(
                !$this->categoryFilterExists($shopCategory->getCategory(), $this->influenceArea)
            );
        }

        return [null, []];
    }
}
