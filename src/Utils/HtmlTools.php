<?php

namespace App\Utils;

use App\Entity\Html;
use App\Entity\Regex;
use App\Entity\Shop;
use App\Repository\HtmlRepository;
use App\Repository\RegexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Process;

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

    public function fetchArticles(Shop $shop, UrlBuilder $urlBuilder) : array
    {
        $pageContent = stripslashes($this->fetchHtmlCode($shop, $urlBuilder->getUrl())->getContent());

        /**
         * @var Regex $regex
         */
        $regex = $this->regexRepository->getPageContentRegex($shop)[0];

        preg_match_all($regex->getContentRegex(), $pageContent, $matches);

        $data = [];


        if (isset($matches[1][0])) {
            $homepage = '';
            if (substr($matches[1][0], 0, 4) !== "http") {
                $homepage = substr(
                    $urlBuilder->getUrl(),
                    0,
                    strpos($urlBuilder->getUrl(), '.lt')
                ) . '.lt';
            }

            for ($i = 0, $iMax = count($matches[1]); $i < $iMax; $i++) {
                $data[] = [
                    'img'   => $homepage . $matches[1][$i],
                    'url'   => $homepage . $matches[2][$i],
                    'title' => $matches[3][$i],
                    'price' => $matches[4][$i],
                ];
            }
        }

        if ($regex->getHtmlReducingRegex() !== null) {
            preg_match($regex->getHtmlReducingRegex(), $pageContent, $match);
            if (isset($match[2]) && $match[2] != 0) {
                $processes = [];
                for ($i = 2, $iMax = ceil($match[2] / $match[1]); $i <= $iMax; $i++) {
                    $process = new Process('../bin/console app:fetchArticles ' .
                        escapeshellarg($urlBuilder->getPage($i)) . ' ' . $shop->getId());
                    $process->start();
                    $processes[] = $process;
                }

                foreach ($processes as $process) {
                    $process->wait();
                    $data = array_merge($data, json_decode($process->getOutput()));
//                    $data[] = $process->getErrorOutput();
                }
            }
        }

        return $data;
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
