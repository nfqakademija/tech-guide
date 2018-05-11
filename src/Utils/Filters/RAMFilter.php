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
        /**
         * @var Regex[] $regexes
         */
        $regexes = $this->retrieveRegexes($shopCategory, $this->influenceAreas[0]);

        if (\count($regexes) > 0) {
            $memoriesAndValues = [];
            preg_match($regexes[0]->getHtmlReducingRegex(), $pageContent, $match);
            if (isset($match[1])) {
                $pageContent = $match[1];

                preg_match_all(
                    $regexes[0]->getContentRegex(),
                    $pageContent,
                    $matches
                );

                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
                }

                preg_match_all(
                    str_replace('sizeValue', 'MB', $regexes[0]->getContentRegex()),
                    $pageContent,
                    $matches
                );
                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $memoriesAndValues[$matches[1][$i]] = $matches[2][$i]
                        / 1024;
                }

                preg_match_all(
                    str_replace('sizeValue', 'GB', $regexes[0]->getContentRegex()),
                    $pageContent,
                    $matches
                );
                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
                }

                return [
                    $regexes[0]->getUrlParameter(),
                    array_keys(\array_slice(
                        $memoriesAndValues,
                        floor($this->influenceBounds['RAM'][0]
                            * \count($memoriesAndValues)),
                        floor($this->influenceBounds['RAM'][1]
                            * \count($memoriesAndValues)),
                        true
                    ))
                ];
            }
        }

        return [null, []];
    }
}
