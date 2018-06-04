<?php

namespace App\Utils\Filters;

use App\Utils\InfluenceCalculator;
use Doctrine\ORM\EntityManagerInterface;

class Filters
{
    private $filters;

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
}
