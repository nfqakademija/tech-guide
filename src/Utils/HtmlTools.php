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
    private const DATE_DIFF = 3;

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

    public function fetchHtmlCode(Shop $shop, string $url) : ?Html
    {
        $htmlEntity = $this->htmlRepository->findByUrl($url);

        if ($htmlEntity === null) {
            try {
                $pageContent = file_get_contents($url);
            } catch (\Exception $exception) {
                return null;
            }
            $htmlEntity = $this->htmlRepository->add($shop, $pageContent, $url);
        } elseif ($htmlEntity->getAddedAt()->diff(new \DateTime('now'))->format('%a') > self::DATE_DIFF) {
            try {
                $pageContent = file_get_contents($url);
            } catch (\Exception $exception) {
                return null;
            }
            $this->htmlRepository->update($htmlEntity, $pageContent);
        }

        return $htmlEntity;
    }
}
