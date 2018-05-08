<?php

namespace App\Utils;

use App\Entity\Category;
use App\Entity\ShopCategory;
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
        Filter::makeInfluenceBoundsCalculator($answers, $entityManager);

        $this->shopCategoryRepository = $entityManager
            ->getRepository(ShopCategory::class);

        $this->category = $entityManager
            ->getRepository(Category::class)
            ->find($answers[0]);
        $this->urlBuilder = new UrlBuilder();

        $this->filters = [
            new PriceFilter($entityManager),
            new ColorFilter($entityManager),
            new MemoryFilter($entityManager),
            new RAMFilter($entityManager),
            new ProcessorFilter($entityManager),
            new SizeFilter($entityManager),
            new ResolutionFilter($entityManager),
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

            $filtersValues = [];
            foreach ($this->filters as $filter) {
                $filtersValues[] = $filter->filter($mainPage, $shopCategory);
            }

            $this->urlBuilder->addFilterArray($filtersValues);
            $urls[] = $this->urlBuilder->getUrl();
        }

        return $urls;
    }

    /**
     * @param string $filter
     *
     * @return array
     */
    private function filterCategory(?string $filter) : array
    {
        if ($filter !== null) {
            return explode('=', $filter);
        }

        return [$filter, []];
    }
}
