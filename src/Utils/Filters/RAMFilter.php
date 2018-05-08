<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;

class RAMFilter extends Filter
{
    private const TYPE = 'RAM';

    /**
     * RAMFilter constructor.
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
            $memoriesAndValues = [];
            /**
             * @var Regex $regex
             */
            $regex = $this->findRegexes($filters[0])[0];
            preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
            if (isset($match[1])) {
                $pageContent = $match[1];

                preg_match_all(
                    $regex->getContentRegex(),
                    $pageContent,
                    $matches
                );

                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
                }

                preg_match_all(
                    str_replace('sizeValue', 'MB', $regex->getContentRegex()),
                    $pageContent,
                    $matches
                );
                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $memoriesAndValues[$matches[1][$i]] = $matches[2][$i]
                        / 1024;
                }

                preg_match_all(
                    str_replace('sizeValue', 'GB', $regex->getContentRegex()),
                    $pageContent,
                    $matches
                );
                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
                }

                return [
                    $filters[0]->getUrlParameter(),
                    array_keys(\array_slice(
                        $memoriesAndValues,
                        floor(self::$influenceBounds['RAM'][0]
                            * \count($memoriesAndValues)),
                        floor(self::$influenceBounds['RAM'][1]
                            * \count($memoriesAndValues)),
                        true
                    ))
                ];
            }
        }

        return [null, []];
    }
}
