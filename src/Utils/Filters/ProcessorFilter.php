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
            $processorsAndValues = [];
            preg_match($regexes[0]->getHtmlReducingRegex(), $pageContent, $match);
            if (isset($match[0])) {
                $pageContent = $match[0];

                preg_match_all($regexes[0]->getContentRegex(), $pageContent, $matches);
                for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                    $processorsAndValues[$matches[1][$i]] = $matches[2][$i];
                }

                asort($processorsAndValues);

                return [
                    $regexes[0]->getUrlParameter(),
                    array_keys(\array_slice(
                        $processorsAndValues,
                        round($this->influenceBounds[self::TYPE][0]
                            * \count($processorsAndValues)),
                        round($this->influenceBounds[self::TYPE][1]
                            * \count($processorsAndValues)),
                        true
                    ))
                ];
            }
        }
        return [null, []];
    }
}
