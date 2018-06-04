<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
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
        
        $cameraFilters = [[]];
        foreach ($regexes as $regex) {
            $content = $this->reduceHtml($regex, $pageContent);
            if ($content !== null) {
                $cameraFilters[] = $this->formatResults($regex, $this->fetchFilterValues($regex->getContentRegex(), $content));
            }
        }

        $this->checkUsage($shopCategory->getCategory());

        return array_merge(...$cameraFilters);
    }
}
