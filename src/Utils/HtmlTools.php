<?php

namespace App\Utils;


use App\Entity\Html;
use App\Entity\Regex;
use App\Entity\Shop;
use App\Repository\HtmlRepository;
use App\Repository\RegexRepository;
use Doctrine\ORM\EntityManagerInterface;

class HtmlTools
{
    /**
     * @var HtmlRepository $htmlRepository
     */
    private $htmlRepository;
    /**
     * @var RegexRepository $regexRepository
     */
    private $regexRepository;

    /**
     * HtmlTools constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->regexRepository = $entityManager
            ->getRepository(Regex::class);
        $this->htmlRepository = $entityManager
            ->getRepository(Html::class);
    }

    public function getUrlCount(Shop $shop, string $url) : int
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
}