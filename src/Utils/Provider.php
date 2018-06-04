<?php

namespace App\Utils;

use App\Entity\Category;
use App\Entity\ShopCategory;
use App\Repository\ShopCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Process;

class Provider
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @var ShopCategoryRepository $shopCategoryRepository
     */
    private $shopCategoryRepository;

    private $answers;

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
        $this->answers = $answers;
        $this->shopCategoryRepository = $entityManager
            ->getRepository(ShopCategory::class);

        $this->category = $entityManager
            ->getRepository(Category::class)
            ->find($answers[0]);
    }

    /**
     * @return array
     */
    public function makeData() : array
    {
        /**
         * @var ShopCategory $shopCategory
         */

        $htmlProcesses = [];

        $shopCategories = $this->shopCategoryRepository->findBy(['category' => $this->category]);
        foreach ($shopCategories as $shopCategory) {
            $process = new Process('../bin/console app:makeUrl ' .
                $shopCategory->getId() . ' ' .
                json_encode($this->answers));
            $process->start();
            $htmlProcesses[] = $process;
        }

        $countProcesses = [];
        $i = 0;
        foreach ($shopCategories as $shopCategory) {
            $htmlProcesses[$i]->wait();
            $data = explode(' ', $htmlProcesses[$i]->getOutput());
            $process = new Process('../bin/console app:fetchData ' .
                $shopCategory->getId() . ' ' .
                escapeshellarg($data[0]) . ' ' . escapeshellarg($data[1]));
            $process->start();
            $countProcesses[] = $process;
            $i++;
        }

        $urls = [];
        /**
         * @var Process $process
         */
        foreach ($countProcesses as $process) {
            $process->wait();
            $urls[] = json_decode($process->getOutput(), true);
        }

        return array_filter($urls);
    }
}
