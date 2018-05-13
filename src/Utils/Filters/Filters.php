<?php

namespace App\Utils\Filters;

use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Repository\RegexRepository;
use App\Utils\InfluenceCalculator;
use Doctrine\ORM\EntityManagerInterface;

class Filters
{
    private $filters;
    /**
     * @var RegexRepository $regexRepository
     */
    private $regexRepository;

    /**
     * Filters constructor.
     *
     * @param array                  $answers
     * @param EntityManagerInterface $entityManager
     *
     * @throws \Exception
     */
    public function __construct(array $answers, EntityManagerInterface $entityManager)
    {
        $this->regexRepository = $entityManager
            ->getRepository(Regex::class);

        $influenceCalculator = new InfluenceCalculator($answers, $entityManager);
        $influenceBounds = $influenceCalculator->calculateInfluenceBounds();

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
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     *
     * @return Filters
     */
    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function fetchPrioritizedRegexes(ShopCategory $shopCategory) : array
    {
        return $this->regexRepository->getRegexesByPriority($shopCategory);
    }
}
