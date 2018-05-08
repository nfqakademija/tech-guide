<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;

class ProcessorFilter extends Filter
{
    private const TYPE = 'Processor';

    /**
     * ProcessorFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
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
            $processorsAndValues = [];
            /**
             * @var Regex $regex
             */
            $regex = $this->findRegexes($filters[0])[0];
            preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
            if (isset($match[0])) {
                $pageContent = $match[0];

                preg_match_all($regex->getContentRegex(), $pageContent, $matches);
                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $processorsAndValues[$matches[1][$i]] = $matches[2][$i];
                }

                asort($processorsAndValues);

                return [
                    $filters[0]->getUrlParameter(),
                    array_keys(\array_slice(
                        $processorsAndValues,
                        round(self::$influenceBounds[self::TYPE][0]
                            * \count($processorsAndValues)),
                        round(self::$influenceBounds[self::TYPE][1]
                            * \count($processorsAndValues)),
                        true
                    ))
                ];
            }
        }
        return [null, []];
    }
}
