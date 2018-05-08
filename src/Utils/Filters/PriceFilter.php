<?php

namespace App\Utils\Filters;

use App\Entity\ShopCategory;
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
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    public function filter(string $pageContent, ShopCategory $shopCategory) : array
    {
        $filters = $this->retrieveFilters($shopCategory);

        if (\count($filters) > 0) {
            $regexes = $this->findRegexes($filters[0]);
            if (\count($regexes) === 1) {
                preg_match_all($regexes[0]->getContentRegex(), $pageContent, $matches);
                $maxValue = explode('-', $matches[1][\count($matches[1]) - 1])[1];

                $value = round($maxValue * $this->influenceBounds['Price'][0]) . '-'
                    . round($maxValue * $this->influenceBounds['Price'][1]);

                return [$filters[0]->getUrlParameter(), [$value]];
            }
        }

        return [null, []];
    }
}
