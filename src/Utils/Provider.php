<?php

namespace App\Utils;

use App\Entity\Category;
use App\Entity\Shop;
use App\Entity\ShopCategory;
use App\Utils\Filters\CameraFilter;
use App\Utils\Filters\ColorFilter;
use App\Utils\Filters\Filter;
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

    private $shopCategoryRepository;

    private $urlBuilder;
    private $filters;

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
        $userAnswers = new UserAnswers($answers, $entityManager);
        $userAnswers->saveAnswers();

        $answers = array_map('\intval', $answers);
        $influenceCalculator = new InfluenceCalculator($answers, $entityManager);
        $influenceBounds = $influenceCalculator->calculateInfluenceBounds();

        $this->shopCategoryRepository = $entityManager
            ->getRepository(ShopCategory::class);

        $this->category = $entityManager
            ->getRepository(Category::class)
            ->find($answers[0]);
        $this->urlBuilder = new UrlBuilder();

        $this->filters = [
            new PriceFilter($entityManager, $influenceBounds),
            new ColorFilter($entityManager, $influenceBounds),
            new MemoryFilter($entityManager, $influenceBounds),
            new RAMFilter($entityManager, $influenceBounds),
            new ProcessorFilter($entityManager, $influenceBounds),
            new SizeFilter($entityManager, $influenceBounds),
            new ResolutionFilter($entityManager, $influenceBounds),
            new CameraFilter($entityManager, $influenceBounds),
        ];
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

            try {
                $mainPage = file_get_contents($this->urlBuilder->getUrl());
            } catch (\Exception $e) {
                continue;
            }

            $filtersValues = [];
            foreach ($this->filters as $filter) {
                $values = array_chunk($filter->filter($mainPage, $shopCategory), 2);
                foreach ($values as $value) {
                    $filtersValues[] = $value;
                }
            }

            $this->urlBuilder->addFilterArray($filtersValues);
            $urls[] = [
                'url' => $this->urlBuilder->getUrl(),
                'logo' => $shopCategory->getShop()->getLogo(),
            ];
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
}
