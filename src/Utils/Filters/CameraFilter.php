<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

class CameraFilter extends Filter
{
    private const TYPE = 'Camera';

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
        /**
         * @var Regex[] $regexes
         */
        $regexes = $this->retrieveRegexes($shopCategory, $this->influenceAreas[0]);
        
        $cameraFilters = [];
        foreach ($regexes as $regex) {
            preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
            $filterUsageCalculator->addValue(true);
            if (isset($match[0])) {
                $mpxAndValues = [];
                $pageContent = $match[0];
                preg_match_all($regex->getContentRegex(), $pageContent, $matches);
            }

            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $mpxAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            asort($mpxAndValues);

            $cameraFilters[] = $regex->getUrlParameter();
            $cameraFilters[] =
                array_keys(\array_slice(
                    $mpxAndValues,
                    round($this->influenceBounds[self::TYPE][0]
                        * \count($mpxAndValues)),
                    round($this->influenceBounds[self::TYPE][1]
                        * \count($mpxAndValues)),
                    true
                ));
        }
        
        if (\count($regexes) === 0) {
            $filterUsageCalculator->addValue(
                !$this->categoryFilterExists($shopCategory->getCategory(), $this->influenceAreas[0])
            );
        }

        return $cameraFilters;
    }
}
