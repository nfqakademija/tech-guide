<?php

namespace App\Utils;


use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\InfluenceArea;
use App\Entity\Shop;
use App\Entity\ShopCategory;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Stichoza\GoogleTranslate\TranslateClient;

class Provider
{
    /**
     * @var Category
     */
    private $category;

    private $shopCategoryRepository;
    private $influenceAreaRepository;

    private $urlBuilder;
    private $influenceBounds;

    /**
     * Provider constructor.
     *
     * @param array                  $answers
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(array $answers, EntityManagerInterface $entityManager)
    {
        $answers = array_map('intval', $answers);
        $influenceCalculator = new InfluenceCalculator($answers, $entityManager);
        $this->influenceBounds = $influenceCalculator->calculateInfluenceBounds();

        $userAnswers = new UserAnswers($answers, $entityManager);
        $userAnswers->saveAnswers();

        $this->shopCategoryRepository = $entityManager
            ->getRepository(ShopCategory::class);
        $this->influenceAreaRepository = $entityManager
            ->getRepository(InfluenceArea::class);

        $this->category = $entityManager
            ->getRepository(Category::class)
            ->find($answers[0]);
        $this->urlBuilder = new UrlBuilder();
    }

    /**
     * @return array
     */
    public function makeUrls() : array
    {
        $url = [];
        /**
         * @var ShopCategory $shopCategory
         */
        foreach ($this->shopCategoryRepository
            ->findBy(['category' => $this->category]) as $shopCategory) {

            $categoryFilter = $this->filterCategory($shopCategory->getCategoryFilter());

            $this->urlBuilder
                ->reset()
                ->addHomePage($shopCategory->getShop()->getHomepage())
                ->addPrefix($shopCategory->getPrefix())
                ->addFilter($categoryFilter[0], [$categoryFilter[1]]);

            try {
                $mainPage = file_get_contents($this->urlBuilder->getUrl());
            } catch (\Exception $e) {
                continue;
            }

            $filtersValues = $this->makeFilters($shopCategory, $mainPage);

            $this->urlBuilder->addFilterArray($filtersValues);


            $url[] = $this->urlBuilder->getUrl();
        }

        return $url;
    }

    /**
     * @param ShopCategory $shopCategory
     * @param string       $pageContent
     *
     * @return array
     */
    private function makeFilters(ShopCategory $shopCategory, string $pageContent) : array
    {
        $filters = [];

        $filters[] = $this->filterPrice($shopCategory->getPriceFilter(), $pageContent);
        $filters[] = $this->chooseMemoryFilter(
            $pageContent,
            $shopCategory->getMemoryFilter(),
            $shopCategory->getSsdFilter(),
            $shopCategory->getHddFilter()
        );
        $filters[] = $this->filterColor($shopCategory->getColorFilter(), $pageContent);
        $filters[] = $this->filterRAM($shopCategory->getRamFilter(), $pageContent);
        $filters[] = $this->filterProcessor($shopCategory->getProcessorFilter(), $pageContent);
        $filters[] = $this->filterSize($shopCategory->getSizeFilter(), $pageContent);
        $filters[] = $this->filterResolution($shopCategory->getResolutionFilter(), $pageContent);

        return $filters;
    }

    /**
     * @param string $filter
     *
     * @return array
     */
    private function filterCategory(?string $filter) : array
    {
        if($filter !== null)
            return explode('=', $filter);

        return [$filter, []];
    }

    /**
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterPrice(string $filter, string $pageContent) : array
    {
        if($this->influenceBounds['Price'][1] !== 0) {
            $regex
                = '#(?<=price&quot;,&quot;value&quot;:&quot;)(\d+-\d+?)&quot;,&quot;label#is';
            preg_match_all($regex, $pageContent, $matches);
            $maxValue = explode('-', $matches[1][\count($matches[1]) - 1])[1];

            $value = round($maxValue * $this->influenceBounds['Price'][0]) . '-'
                . round($maxValue * $this->influenceBounds['Price'][1]);

            return [$filter, [$value]];
        }

        return [$filter, []];
    }

    /**
     * use later
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterRAM(?string $filter, string $pageContent) : array
    {
        if($filter !== null) {
            $memoriesAndValues = [];

            preg_match('#[(]RAM[)] [(]GB[)]&quot;(.*)#is', $pageContent, $match);
            $pageContent = $match[1];

            $regex = '#(\d+?)&quot;,&quot;label&quot;:&quot;(\d+?) MB#is';
            preg_match_all($regex, $pageContent, $matches);
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i] / 1024;
            }

            $regex = str_replace('MB', 'GB', $regex);
            preg_match_all($regex, $pageContent, $matches);
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            return [
                $filter,
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

        return [$filter, []];
    }

    /**
     * @param string      $pageContent
     * @param null|string $memoryFilter
     * @param null|string $ssdFilter
     * @param null|string $hddFilter
     *
     * @return array
     */
    private function chooseMemoryFilter(string $pageContent, ?string $memoryFilter, ?string $ssdFilter, ?string $hddFilter) : array
    {
        if($memoryFilter !== null) {
            return  $this->filterMemory($memoryFilter, $pageContent);
        }

        if (
            $ssdFilter !== null &&
            $this->influenceBounds['SSD'][1] > $this->influenceBounds['HDD'][1]
        ) {
            return $this->filterSSD($ssdFilter, $pageContent);
        }
        if ($hddFilter !== null) {
            return $this->filterHDD($hddFilter, $pageContent);
        }

        return [null, []];
    }

    /**
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterMemory(string $filter, string $pageContent) : array
    {
        if ($this->influenceBounds['Memory'][1] !== 0) {
            $memoriesAndValues = [];
            preg_match('#u0117 atmintis(.*)#is', $pageContent, $match);
            $pageContent = $match[1];
            $regex
                = '#(\d+?)&quot;,&quot;label&quot;:&quot;(\d+?)&quot;,&quot;image&#is';
            preg_match_all($regex, $pageContent, $matches);

            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            asort($memoriesAndValues);

            return [
                $filter,
                array_keys(\array_slice(
                    $memoriesAndValues,
                    round($this->influenceBounds['Memory'][0]
                        * \count($memoriesAndValues)),
                    round($this->influenceBounds['Memory'][1]
                        * \count($memoriesAndValues)),
                    true
                ))
            ];
        }

        return [$filter, []];
    }

    /**
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterSSD(string $filter, string $pageContent) : array
    {
        if ($this->influenceBounds['SSD'][1] !== 0) {
            preg_match('#SSD talpa(.*)HDD talpa#is', $pageContent, $match);
            $pageContent = $match[1];

            $memoriesAndValues = [];
            $regex
                = '#(\d+?)&quot;,&quot;label&quot;:&quot;(\d+?) GB#';
            preg_match_all($regex, $pageContent, $matches);

            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            $regex = str_replace('GB', 'TB', $regex);
            preg_match_all($regex, $pageContent, $matches);
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i] * 1024;
            }

            asort($memoriesAndValues);

            return [
                $filter,
                array_keys(\array_slice(
                    $memoriesAndValues,
                    round($this->influenceBounds['Memory'][0]
                        * \count($memoriesAndValues)),
                    round($this->influenceBounds['Memory'][1]
                        * \count($memoriesAndValues)),
                    true
                ))
            ];
        }

        return [$filter, []];
    }

    /**
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterHDD(string $filter, string $pageContent) : array
    {
        if ($this->influenceBounds['HDD'][1] !== 0) {
            preg_match('#HDD talpa(.*)Atmintin.u0117 [(]RAM[)]#is', $pageContent, $match);
            $pageContent = $match[1];

            $memoriesAndValues = [];
            $regex
                = '#(\d+?)&quot;,&quot;label&quot;:&quot;(\d+?) GB#';
            preg_match_all($regex, $pageContent, $matches);

            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i] / 1024;
            }

            $regex = str_replace('GB', 'TB', $regex);
            preg_match_all($regex, $pageContent, $matches);
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            asort($memoriesAndValues);
            return [
                $filter,
                array_keys(\array_slice(
                    $memoriesAndValues,
                    floor($this->influenceBounds['Memory'][0]
                        * \count($memoriesAndValues)),
                    ceil($this->influenceBounds['Memory'][1]
                        * \count($memoriesAndValues)),
                    true
                ))
            ];
        }

        return [$filter, []];
    }


    /**
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterColor(?string $filter, string $pageContent) : array
    {
        if($filter !== null && isset($this->influenceBounds['Color'][0])) {
            $answers
                = $this->influenceAreaRepository->findBy(['content' => 'Color'])[0]->getQuestions()[0]
                ->getAnswers()
                ->filter(function (Answer $answer) {
                    return $answer->getValue()
                        === $this->influenceBounds['Color'][0];
                });

            $colorName = '';
            foreach ($answers as $answer) {
                $colorName = TranslateClient::translate('en', 'lt',
                    $answer->getContent() . " color");
                $colorName = mb_substr(explode(' ', $colorName)[0], 0, -1);
            }

            $regex = '#&quot;(\d{7})?&quot;,&quot;label&quot;:&quot;([^\s]\s)?'
                . $colorName
                . '.{1,6}&quot;,&quot;image&quot;:&quot;&quot;},#is';
            preg_match_all($regex, $pageContent, $matches);

            return [$filter, $matches[1]];
        }

        return [$filter, []];
    }

    private function filterProcessor(?string $filter, string $pageContent) : array
    {
        if($filter !== null && $this->influenceBounds['Processor'][1] !== 0) {
            preg_match('#Procesoriaus tipas(.*)Gylis#is', $pageContent, $match);
            $pageContent = $match[1];

            $processorsAndValues = [];
            $regex
                = '#(\d+?)&quot;,&quot;label&quot;:&quot;Intel.u00ae Core.u2122 i(\d)#';
            preg_match_all($regex, $pageContent, $matches);
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $processorsAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            asort($processorsAndValues);

            return [
                $filter,
                array_keys(\array_slice(
                    $processorsAndValues,
                    round($this->influenceBounds['Processor'][0]
                        * \count($processorsAndValues)),
                    round($this->influenceBounds['Processor'][1]
                        * \count($processorsAndValues)),
                    true
                ))
            ];
        }
        return [$filter, []];
    }

    private function filterSize(?string $filter, string $pageContent) : array
    {
        if ($filter !== null && $this->influenceBounds['Size'][1] !== 0) {
            preg_match('#u012estri.u017eain(.*)Ekrano rai.u0161ka#is', $pageContent, $match);
            $pageContent = $match[1];

            $sizesAndValues = [];
            $regex
                = '#(\d+?)&quot;,&quot;label&quot;:&quot;(\d)#';
            preg_match_all($regex, $pageContent, $matches);
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $sizesAndValues[$matches[1][$i]] = $matches[2][$i];
            }

            asort($sizesAndValues);

            return [
                $filter,
                array_keys(\array_slice(
                    $sizesAndValues,
                    round($this->influenceBounds['Size'][0]
                        * \count($sizesAndValues)),
                    round($this->influenceBounds['Size'][1]
                        * \count($sizesAndValues)),
                    true
                ))
            ];
        }

        return [$filter, []];
    }

    private function filterResolution(?string $filter, string $pageContent) : array
    {
        if (
            $filter !== null &&
            isset($this->influenceBounds['Resolution'][1]) &&
            $this->influenceBounds['Resolution'][1] !== 0
        ) {
            preg_match('#Ekrano rai.u0161ka(.*)Komercinis televizorius#is', $pageContent, $match);
            $pageContent = $match[1];

            $resolutionAndValues = [];
            $regex
                = '#(\d+?)&quot;,&quot;label&quot;:&quot;(\d+) x (\d+)#';
            preg_match_all($regex, $pageContent, $matches);
            for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
                $resolutionAndValues[$matches[1][$i]] = $matches[2][$i] * $matches[3][$i];
            }

            asort($resolutionAndValues);

            return [
                $filter,
                array_keys(\array_slice(
                    $resolutionAndValues,
                    round($this->influenceBounds['Resolution'][0]
                        * \count($resolutionAndValues)),
                    round($this->influenceBounds['Resolution'][1]
                        * \count($resolutionAndValues)),
                    true
                ))
            ];
        }

        return [$filter, []];
    }
}
