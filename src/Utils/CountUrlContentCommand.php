<?php

namespace App\Utils;

use App\Entity\FilterUsage;
use App\Entity\Html;
use App\Entity\Regex;
use App\Entity\ShopCategory;
use App\Repository\FilterUsageRepository;
use App\Repository\HtmlRepository;
use App\Repository\RegexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CountUrlContentCommand extends ContainerAwareCommand
{
    private $shopCategoryRepository;

    /**
     * @var FilterUsageRepository $htmlRepository
     */
    private $filterUsageRepository;
    /**
     * @var RegexRepository $regexRepository
     */
    private $regexRepository;

    /**
     * @var $urlBuilder UrlBuilder
     */
    private $urlBuilder;
    private $filterUsageCalculator;
    private $htmlTools;

    /**
     * CountUrlContentCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FilterUsageCalculator  $filterUsageCalculator
     * @param HtmlTools              $htmlTools
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FilterUsageCalculator $filterUsageCalculator,
        HtmlTools $htmlTools
    ) {
        $this->shopCategoryRepository = $entityManager->getRepository(ShopCategory::class);
        $this->filterUsageRepository = $entityManager->getRepository(FilterUsage::class);
        $this->regexRepository = $entityManager->getRepository(Regex::class);

        $this->filterUsageCalculator = $filterUsageCalculator;
        $this->htmlTools = $htmlTools;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:countContent')
            ->addArgument('shopCategoryId')
            ->addArgument('urlBuilder')
            ->addArgument('filterUsageCalculator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $shopCategory = $this->shopCategoryRepository->find($input->getArgument('shopCategoryId'));
        $this->urlBuilder = $serializer->deserialize($input->getArgument('urlBuilder'), UrlBuilder::class, 'json');
        $this->filterUsageCalculator->setValues(json_decode($input->getArgument('filterUsageCalculator')));

        $count = $this->htmlTools->getUrlCount($shopCategory->getShop(), $this->urlBuilder->getUrl());
        if ($count === 0) {
            $filters = $this->regexRepository->getRegexesByPriority($shopCategory);
            do {
                array_splice($filters, 0, 1);
                if ($this->urlBuilder->removeFilter($filters[0]['urlParameter'])) {
                    $this->filterUsageCalculator->replaceWithFalse();
                }
                $count = $this->htmlTools->getUrlCount($shopCategory->getShop(), $this->urlBuilder->getUrl());
            } while ($count === 0);
        }

        /** change this later */
        $html = $this->htmlTools->fetchHtmlCode($shopCategory->getShop(), $this->urlBuilder->getUrl());

        $filterUsage = $this->filterUsageCalculator->calculate();
        $this->filterUsageRepository->add($html, $filterUsage);
        $output->writeln(json_encode([
            'url' => $this->urlBuilder->getUrl(),
            'logo' => $shopCategory->getShop()->getLogo(),
            'filterUsage' => $filterUsage,
            'count' => $count
        ]));
    }
}
