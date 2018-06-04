<?php

namespace App\Utils\Commands;

use App\Entity\Shop;
use App\Entity\ShopCategory;
use App\Utils\Filters\Filter;
use App\Utils\Filters\Filters;
use App\Utils\HtmlTools;
use App\Utils\UrlBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MakeUrlCommand extends ContainerAwareCommand
{
    private $entityManager;
    private $shopCategoryRepository;

    private $urlBuilder;

    private $htmlTools;

    /**
     * MakeUrlCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UrlBuilder             $urlBuilder
     * @param HtmlTools              $htmlTools
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlBuilder $urlBuilder,
        HtmlTools $htmlTools
    ) {
        $this->entityManager = $entityManager;
        $this->shopCategoryRepository = $entityManager->getRepository(ShopCategory::class);
        $this->urlBuilder = $urlBuilder;
        $this->htmlTools = $htmlTools;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:makeUrl')
            ->addArgument('shopCategoryId')
            ->addArgument('answers');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shopCategoryId = $input->getArgument('shopCategoryId');
        $answers = json_decode($input->getArgument('answers'));

        /**
         * @var ShopCategory $shopCategory
         */
        $shopCategory = $this->shopCategoryRepository->find($shopCategoryId);

        $categoryFilter = $this->filterCategory(
            $shopCategory->getCategoryFilter(),
            $shopCategory->getShop()
        );

        $this->urlBuilder
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

        if (($html = $this->htmlTools->fetchHtmlCode($shopCategory->getShop(), $this->urlBuilder->getUrl())) === null) {
            $output->writeln('');
            die();
        }

        $mainPage = stripslashes($html->getContent());

        $filters = new Filters($answers, $this->entityManager);
        $filtersValues = [];

        /**
         * @var Filter $filter
         */
        $wereAdded = [];
        foreach ($filters->getFilters() as $filter) {
            $values = array_chunk($filter->filter($mainPage, $shopCategory), 2);
            $wereAdded[] = $filter->isUsed();
            foreach ($values as $value) {
                $filtersValues[] = $value;
            }
        }

        $this->urlBuilder->addFilterArray($filtersValues);
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $output->writeln($serializer->serialize($this->urlBuilder, 'json') . ' ');
        $output->writeln(json_encode($wereAdded));
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
