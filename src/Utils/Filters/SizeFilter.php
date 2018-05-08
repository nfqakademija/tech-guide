<?php

namespace App\Utils\Filters;


use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;

class SizeFilter extends Filter
{
    private const type = 'Size';

    /**
     * PriceFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->influenceAreas = $this->findInfluenceAreas([self::type]);
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
            $sizesAndValues = [];
            /**
             * @var Regex $regex
             */
            $regex = $this->findRegexes($filters[0])[0];
            preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
            if(isset($match[1])) {
                $pageContent = $match[1];

                preg_match_all($regex->getContentRegex(), $pageContent,
                    $matches);

                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $sizesAndValues[$matches[1][$i]] = $matches[2][$i];
                }

                asort($sizesAndValues);

                return [
                    $filters[0]->getUrlParameter(),
                    array_keys(\array_slice(
                        $sizesAndValues,
                        round(self::$influenceBounds[self::type][0]
                            * \count($sizesAndValues)),
                        round(self::$influenceBounds[self::type][1]
                            * \count($sizesAndValues)),
                        true
                    ))
                ];
            }
            //filter has been removed or its bound were changed.
            //do something about it
        }

        return [null, []];
    }
}