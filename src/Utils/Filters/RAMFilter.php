<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

class RAMFilter extends Filter
{
    /**
     * RAMFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds, 'RAM');
    }


    /**
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    public function filter(string $pageContent, ShopCategory $shopCategory) : array {
        /**
         * @var Regex[] $regexes
         */
        $regexes = $this->retrieveRegexes($shopCategory, $this->influenceArea);

        if (\count($regexes) > 0) {
            $pageContent = $this->reduceHtml($regexes[0], $pageContent);
            if ($pageContent !== null) {
                $memoriesAndValues = $this->fetchFilterValues($regexes[0]->getContentRegex(), $pageContent);
                if(strpos($regexes[0]->getContentRegex(), 'sizeValue')) {
                    $memoriesAndValues += $this->fetchFilterValues(str_replace('sizeValue', 'MB',
                        $regexes[0]->getContentRegex()), $pageContent, 1 / 1024) +
                    $this->fetchFilterValues(str_replace('sizeValue', 'GB',
                        $regexes[0]->getContentRegex()), $pageContent);
                }

                return $this->formatResults($regexes[0], $memoriesAndValues);
            }
        }

        $this->checkUsage($shopCategory->getCategory());
        return [null, []];
    }
}
