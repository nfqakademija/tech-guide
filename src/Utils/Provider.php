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
    private $answerRepository;

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
        $influenceCalculator = new InfluenceCalculator($answers, $entityManager);
        $this->influenceBounds = $influenceCalculator->calculateInfluenceBounds();

        $this->shopCategoryRepository = $entityManager
            ->getRepository(ShopCategory::class);
        $this->influenceAreaRepository = $entityManager
            ->getRepository(InfluenceArea::class);
        $this->answerRepository = $entityManager
            ->getRepository(Answer::class);

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

            $mainPage = file_get_contents($this->urlBuilder->getUrl());
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
        $priceFilter = $this->filterPrice($shopCategory->getPriceFilter(), $pageContent);
        $memoryFilter = $this->filterMemory($shopCategory->getMemoryFilter(), $pageContent);
        $colorFilter = $this->filterColor($shopCategory->getColorFilter(), $pageContent);
        return [
            [$priceFilter[0], $priceFilter[1]],
            [$memoryFilter[0], $memoryFilter[1]],
            [$colorFilter[0], $colorFilter[1]]
        ];
    }

    /**
     * @param string $filter
     *
     * @return array
     */
    private function filterCategory(string $filter) : array
    {
        return explode('=', $filter);
    }

    /**
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterPrice(string $filter, string $pageContent) : array
    {
        $regex = '#(?<=price&quot;,&quot;value&quot;:&quot;)(\d+-\d+?)&quot;,&quot;label#is';
        preg_match_all($regex, $pageContent, $matches);
        $maxValue = explode('-', $matches[1][\count($matches[1]) - 1])[1];

        $value = round($maxValue * $this->influenceBounds['Price'][0]) . '-'
            . round($maxValue * $this->influenceBounds['Price'][1]);

        return [$filter, [$value]];
    }

    /**
     * use later
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterRAM(string $filter, string $pageContent) : array
    {
        $memoriesAndValues = [];
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

        return [$filter, array_keys( \array_slice(
            $memoriesAndValues,
            round($this->influenceBounds['Memory'][0] * \count($memoriesAndValues)),
            round($this->influenceBounds['Memory'][1] * \count($memoriesAndValues)),
            true
        ))[1]];
    }

    /**
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterMemory(string $filter, string $pageContent) : array
    {
        $memoriesAndValues = [];
        $pageRegex = " ";
        preg_match('#u0117 atmintis(.*)#is', $pageContent, $match);
        $pageContent = $match[1];
        $regex = '#(\d+?)&quot;,&quot;label&quot;:&quot;(\d+?)&quot;,&quot;image&#is';
        preg_match_all($regex, $pageContent, $matches);

        for ($i = 0, $iMax = \count($matches[0]); $i < $iMax; $i++) {
            $memoriesAndValues[$matches[1][$i]] = $matches[2][$i];
        }

        asort($memoriesAndValues);

        return [$filter, array_keys( \array_slice(
            $memoriesAndValues,
            round($this->influenceBounds['Memory'][0] * \count($memoriesAndValues)),
            round($this->influenceBounds['Memory'][1] * \count($memoriesAndValues)),
            true
        ))];
    }

    /**
     * @param string $filter
     * @param string $pageContent
     *
     * @return array
     */
    private function filterColor(string $filter, string $pageContent) : array
    {
        if(isset($this->influenceBounds['Color'][0])) {
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
}