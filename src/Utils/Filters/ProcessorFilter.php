<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

class ProcessorFilter extends Filter
{
    /**
     * ProcessorFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds, 'Processor');
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
                return $this->formatResults(
                    $regexes[0],
                    $this->fetchFilterValues($regexes[0]->getContentRegex(), $pageContent)
                );
            }
        }

        $this->checkUsage($shopCategory->getCategory());
        return [null, []];
    }
}
