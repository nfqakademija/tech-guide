<?php

namespace App\Utils\Filters;


use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Filter as EntityFilter;

class MemoryFilter extends Filter
{
    private const type = 'Memory';
    private const subtype1 = 'SSD';
    private const subtype2 = 'HDD';

    /**
     * MemoryFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->influenceAreas = $this->findInfluenceAreas([self::type, self::subtype1, self::subtype2]);
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

        if(\count($filters) === 1) {
            return $this->filterMemory($pageContent, $filters[0]);
        }

        //replace filter[0] with exact ssd filter
        //same with filter[1]
        if (\count($filters) > 1) {
            if (self::$influenceBounds['SSD'][1] > self::$influenceBounds['HDD'][1]) {
                return $this->filterSubType($pageContent, $filters[0]);
            }

            return $this->filterSubType($pageContent, $filters[1]);
        }

        return [null, []];
    }

    /**
     * @param string       $pageContent
     * @param EntityFilter $filter
     *
     * @return array
     */
    private function filterMemory(string $pageContent, EntityFilter $filter) : array
    {
        $memoriesAndValues = [];
        /**
         * @var Regex $regex
         */
        $regex = $this->findRegexes($filter)[0];
        preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
        if(isset($match[1])) {
            $pageContent = $match[1];

            preg_match_all($regex->getContentRegex(), $pageContent, $matches);

            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            asort($memoriesAndValues);

            return [
                $filter->getUrlParameter(),
                array_keys(\array_slice(
                    $memoriesAndValues,
                    round(self::$influenceBounds[self::type][0]
                        * \count($memoriesAndValues)),
                    round(self::$influenceBounds[self::type][1]
                        * \count($memoriesAndValues)),
                    true
                ))
            ];
        }
    }

    /**
     * @param string       $pageContent
     * @param EntityFilter $filter
     *
     * @return array
     */
    private function filterSubType(string $pageContent, EntityFilter $filter) : array
    {
        $memoriesAndValues = [];
        /**
         * @var Regex $regex
         */
        $regex = $this->findRegexes($filter)[0];
        preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
        if(isset($match[1])) {
            $pageContent = $match[1];

            preg_match_all(
                str_replace('sizeValue', 'GB', $regex->getContentRegex()),
                $pageContent,
                $matches
            );
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            preg_match_all(
                str_replace('sizeValue', 'TB', $regex->getContentRegex()),
                $pageContent,
                $matches
            );
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i] * 1024;
            }

            asort($memoriesAndValues);

            return [
                $filter->getUrlParameter(),
                array_keys(\array_slice(
                    $memoriesAndValues,
                    round(self::$influenceBounds[self::type][0]
                        * \count($memoriesAndValues)),
                    round(self::$influenceBounds[self::type][1]
                        * \count($memoriesAndValues)),
                    true
                ))
            ];
        }
    }
}