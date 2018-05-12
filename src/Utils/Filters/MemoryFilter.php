<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Utils\FilterUsageCalculator;
use Doctrine\ORM\EntityManagerInterface;

class MemoryFilter extends Filter
{
    private const TYPE = 'Memory';
    private const SUBTYPE1 = 'SSD';
    private const SUBTYPE2 = 'HDD';

    /**
     * MemoryFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds);
        $this->influenceAreas = $this->findInfluenceAreas([self::TYPE, self::SUBTYPE1, self::SUBTYPE2]);
    }


    /**
     * @param string                $pageContent
     * @param ShopCategory          $shopCategory
     * @param FilterUsageCalculator $filterUsageCalculator
     *
     * @return array
     */
    public function filter(
        string $pageContent,
        ShopCategory $shopCategory,
        FilterUsageCalculator $filterUsageCalculator
    ) : array {
        $ssdRegexes = $this->retrieveRegexes($shopCategory, $this->influenceAreas[1]);
        $hddRegexes = $this->retrieveRegexes($shopCategory, $this->influenceAreas[2]);

        if (!empty($ssdRegexes) && !empty($hddRegexes)) {
            $filterUsageCalculator->addValue(true);
            if ($this->influenceBounds['SSD'][1] > $this->influenceBounds['HDD'][1]) {
                return $this->filterSubtype($pageContent, $ssdRegexes[0]);
            }

            return $this->filterSubtype($pageContent, $hddRegexes[0]);
        }

        $memoryRegexes = $this->retrieveRegexes($shopCategory, $this->influenceAreas[0]);

        if (!empty($memoryRegexes)) {
            $filterUsageCalculator->addValue(true);
            return $this->filterMemory($pageContent, $memoryRegexes[0]);
        }

        $filterUsageCalculator->addValue(
            !$this->categoryFilterExists($shopCategory->getCategory(), $this->influenceAreas[0]) &&
            !$this->categoryFilterExists($shopCategory->getCategory(), $this->influenceAreas[1]) &&
            !$this->categoryFilterExists($shopCategory->getCategory(), $this->influenceAreas[2])
        );
        return [null, []];
    }

    /**
     * @param string $pageContent
     * @param Regex  $regex
     *
     * @return array
     */
    private function filterMemory(string $pageContent, Regex $regex) : array
    {
        $memoriesAndValues = [];
        preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
        if (isset($match[1])) {
            $pageContent = $match[1];

            preg_match_all($regex->getContentRegex(), $pageContent, $matches);

            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            asort($memoriesAndValues);

            return [
                $regex->getUrlParameter(),
                array_keys(\array_slice(
                    $memoriesAndValues,
                    round($this->influenceBounds[self::TYPE][0]
                        * \count($memoriesAndValues)),
                    round($this->influenceBounds[self::TYPE][1]
                        * \count($memoriesAndValues)),
                    true
                ))
            ];
        }

        return [null, []];
    }

    /**
     * @param string $pageContent
     * @param Regex  $regex
     *
     * @return array
     */
    private function filterSubtype(string $pageContent, Regex $regex) : array
    {
        $memoriesAndValues = [];
        preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
        if (isset($match[1])) {
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
                $regex->getUrlParameter(),
                array_keys(\array_slice(
                    $memoriesAndValues,
                    round($this->influenceBounds[self::TYPE][0]
                        * \count($memoriesAndValues)),
                    round($this->influenceBounds[self::TYPE][1]
                        * \count($memoriesAndValues)),
                    true
                ))
            ];
        }
    }
}
