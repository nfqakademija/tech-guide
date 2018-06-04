<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use Doctrine\ORM\EntityManagerInterface;

class MemoryFilter extends Filter
{
    private $ssdInfluenceArea;
    private $hddInfluenceArea;

    /**
     * MemoryFilter constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $influenceBounds
     */
    public function __construct(EntityManagerInterface $entityManager, array $influenceBounds)
    {
        parent::__construct($entityManager, $influenceBounds, 'Memory');
        $this->ssdInfluenceArea = $this->findInfluenceArea('SSD');
        $this->hddInfluenceArea = $this->findInfluenceArea('HDD');
    }


    /**
     * @param string       $pageContent
     * @param ShopCategory $shopCategory
     *
     * @return array
     */
    public function filter(string $pageContent, ShopCategory $shopCategory) : array {
        $ssdRegexes = $this->retrieveRegexes($shopCategory, $this->ssdInfluenceArea);
        $hddRegexes = $this->retrieveRegexes($shopCategory, $this->hddInfluenceArea);

        if (!empty($ssdRegexes) && !empty($hddRegexes)) {
            if ($this->influenceBounds['SSD'][1] > $this->influenceBounds['HDD'][1]) {
                return $this->filterSubtype($pageContent, $ssdRegexes[0]);
            }

            return $this->filterSubtype($pageContent, $hddRegexes[0]);
        }

        $memoryRegexes = $this->retrieveRegexes($shopCategory, $this->influenceArea);

        if (!empty($memoryRegexes)) {
            return $this->filterMemory($pageContent, $memoryRegexes[0]);
        }

        $this->checkUsage($shopCategory->getCategory());
        if (!$this->isUsed) {
            $this->checkInfluenceAreaUsage($shopCategory->getCategory(), $this->ssdInfluenceArea);
        }

        if (!$this->isUsed) {
            $this->checkInfluenceAreaUsage($shopCategory->getCategory(), $this->hddInfluenceArea);
        }

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
        $pageContent = $this->reduceHtml($regex, $pageContent);
        if ($pageContent !== null) {
            return $this->formatResults($regex, $this->fetchFilterValues($regex->getContentRegex(), $pageContent));
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
        $pageContent = $this->reduceHtml($regex, $pageContent);
        if ($pageContent !== null) {
            $memoriesAndValues =
                $this->fetchFilterValues(str_replace('sizeValue', 'GB', $regex->getContentRegex()), $pageContent) +
                $this->fetchFilterValues(str_replace('sizeValue', 'TB', $regex->getContentRegex()), $pageContent, 1024);

            return $this->formatResults($regex, $memoriesAndValues);
        }
    }
}
