<?php

namespace App\Utils;

use App\Entity\Category;
use App\Entity\Html;
use App\Entity\InfluenceArea;
use App\Entity\Regex;
use App\Entity\Shop;
use App\Entity\ShopCategory;
use App\Repository\HtmlRepository;
use App\Repository\InfluenceAreaRepository;
use App\Repository\RegexRepository;
use App\Repository\ShopCategoryRepository;
use App\Utils\Filters\CameraFilter;
use App\Utils\Filters\ColorFilter;
use App\Utils\Filters\Filter;
use App\Utils\Filters\Filters;
use App\Utils\Filters\MemoryFilter;
use App\Utils\Filters\PriceFilter;
use App\Utils\Filters\ProcessorFilter;
use App\Utils\Filters\RAMFilter;
use App\Utils\Filters\ResolutionFilter;
use App\Utils\Filters\SizeFilter;
use Doctrine\ORM\EntityManagerInterface;

class Provider
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @var HtmlRepository $htmlRepository
     */
    private $htmlRepository;
    /**
     * @var RegexRepository $regexRepository
     */
    private $regexRepository;
    /**
     * @var ShopCategoryRepository $shopCategoryRepository
     */
    private $shopCategoryRepository;

    private $urlBuilder;
    private $filterUsageCalculator;
    private $filters;

    private const DATE_DIFF = 3;

    /**
     * Provider constructor.
     *
     * @param array                  $answers
     * @param EntityManagerInterface $entityManager
     *
     * @throws \Exception
     */
    public function __construct(array $answers, EntityManagerInterface $entityManager)
    {
        $this->filters = new Filters($answers, $entityManager);

        $this->regexRepository = $entityManager
            ->getRepository(Regex::class);
        $this->htmlRepository = $entityManager
            ->getRepository(Html::class);
        $this->shopCategoryRepository = $entityManager
            ->getRepository(ShopCategory::class);

        $this->category = $entityManager
            ->getRepository(Category::class)
            ->find($answers[0]);

        $this->urlBuilder = new UrlBuilder();
        $this->filterUsageCalculator = new FilterUsageCalculator();
    }

    /**
     * @return array
     */
    public function makeUrls() : array
    {
        $urls = [];
        /**
         * @var ShopCategory $shopCategory
         */
        foreach ($this->shopCategoryRepository
            ->findBy(['category' => $this->category]) as $shopCategory) {
            $categoryFilter = $this->filterCategory(
                $shopCategory->getCategoryFilter(),
                $shopCategory->getShop()
            );

            $this->urlBuilder
                ->reset()
                ->setRepeatingFilter($shopCategory->getShop()->getRepeatingFilter())
                ->addHomePage($shopCategory->getShop()->getHomepage())
                ->addPrefix($shopCategory->getPrefix())
                ->addFilterSeparators(
                    $shopCategory->getShop()->getFilterSeparator(),
                    $shopCategory->getShop()->getFirstFilterSeparator()
                )
                ->addFilterValueSeparators(
                    $shopCategory->getShop()->getFilterValueSeparator(),
                    $shopCategory->getShop()->getFirstFilterValueSeparator()
                )
                ->addFilter($categoryFilter[0], [$categoryFilter[1]]);

            if (($mainPage = $this->fetchHtmlCode($shopCategory->getShop())) === '') {
                continue;
            }

            $filtersValues = [];

            /**
             * @var Filter $filter
             */
            foreach ($this->filters->getFilters() as $filter) {
                $values = array_chunk($filter->filter($mainPage, $shopCategory, $this->filterUsageCalculator), 2);
                foreach ($values as $value) {
                    $filtersValues[] = $value;
                }
            }

            $this->urlBuilder->addFilterArray($filtersValues);
            $count = $this->getUrlCount($shopCategory->getShop(), $this->urlBuilder->getUrl());
            $isAlternativeResult = false;
            if ($count === 0) {
                $filters = $this->filters->fetchPrioritizedRegexes($shopCategory);
                $isAlternativeResult = true;
                do {
                    array_splice($filters, 0, 1);
                    if ($this->urlBuilder->removeFilter($filters[0]['urlParameter'])) {
                        $this->filterUsageCalculator->replaceWithFalse();
                    }
                    $count = $this->getUrlCount($shopCategory->getShop(), $this->urlBuilder->getUrl());
                } while ($count === 0);
            }

            $urls[] = [
                'url' => $this->urlBuilder->getUrl(),
                'logo' => $shopCategory->getShop()->getLogo(),
                'filterUsage' => $this->filterUsageCalculator->calculate(),
                'count' => $this->getUrlCount($shopCategory->getShop(), $this->urlBuilder->getUrl()),
                'isAlternativeResult' => $isAlternativeResult
            ];

            $this->filterUsageCalculator->reset();
        }

        return $urls;
    }

    /**
     * @param null|string $filter
     * @param Shop        $shop
     *
     * @return array
     */
    private function filterCategory(?string $filter, Shop $shop) : array
    {
        if ($filter !== null) {
            return explode($shop->getFirstFilterValueSeparator() ?? $shop->getFilterValueSeparator(), $filter);
        }

        return [$filter, []];
    }

    private function getUrlCount(Shop $shop, string $url) : int
    {
        /**
         * @var Regex[] $regexes
         */
        $regexes = $this->regexRepository->getPageContentRegex($shop);
        if (isset($regexes[0])) {
            try {
                $pageContent = file_get_contents($url);
            } catch (\Exception $e) {
                return -1;
            }

            preg_match_all($regexes[0]->getContentRegex(), $pageContent, $matches);
            if (isset($matches[1][0])) {
                return $matches[1][0];
            }
        }

        return -1;
    }

    private function fetchHtmlCode(Shop $shop) : string
    {
        $htmlEntity = $this->htmlRepository->findByUrl($this->urlBuilder->getUrl());

        if ($htmlEntity === null) {
            try {
                $pageContent = file_get_contents($this->urlBuilder->getUrl());
            } catch (\Exception $exception) {
                return '';
            }
            $htmlEntity = $this->htmlRepository->add($shop, $pageContent, $this->urlBuilder->getUrl());
        } elseif ($htmlEntity->getAddedAt()->diff(new \DateTime('now'))->format('%a') > self::DATE_DIFF) {
            try {
                $pageContent = file_get_contents($this->urlBuilder->getUrl());
            } catch (\Exception $exception) {
                return '';
            }
            $this->htmlRepository->update($htmlEntity, $pageContent);
        }

        return stripslashes($htmlEntity->getContent());
    }
}
