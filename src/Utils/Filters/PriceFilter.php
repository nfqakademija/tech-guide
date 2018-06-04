<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;

class PriceFilter extends Filter
{
    /**
     * PriceFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds, 'Price');
    }


    /**
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    public function filter(string $pageContent, ShopCategory $shopCategory) : array {
        if ($this->influenceBounds[$this->type][0] === $this->influenceBounds[$this->type][1]) {
            return [null, []];
        }

        /**
         * @var Regex[] $regexes
         */
        $regexes = $this->retrieveRegexes($shopCategory, $this->influenceArea);

        if (\count($regexes) > 0) {
            if (\count($regexes) === 1) {
                preg_match_all(
                    $regexes[0]->getContentRegex(),
                    $pageContent,
                    $matches
                );

                $value = round($matches[1][0]
                        * $this->influenceBounds[$this->type][0]) . $shopCategory->getShop()->getPriceSeparator()
                    . round($matches[1][0]
                        * $this->influenceBounds[$this->type][1]);

                $this->isUsed = true;
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
                    * $this->influenceBounds[$this->type][0] + $min[1][0]);
                $maxValue = round($max[1][0]
                    * $this->influenceBounds[$this->type][1]);


                $this->isUsed = true;
                return [
                    $regexes[0]->getUrlParameter(), [$minValue],
                    $regexes[1]->getUrlParameter(), [$maxValue]
                ];
            }
        }

        $this->checkUsage($shopCategory->getCategory());
        return [null, []];
    }
}
