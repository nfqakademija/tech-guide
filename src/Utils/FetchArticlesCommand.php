<?php

namespace App\Utils;

use App\Entity\Regex;
use App\Entity\Shop;
use App\Repository\RegexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchArticlesCommand extends ContainerAwareCommand
{
    private $htmlTools;
    private $shopRepository;
    /**
     * @var RegexRepository $regexRepository
     */
    private $regexRepository;

    /**
     * FetchArticlesCommand constructor.
     *
     * @param $htmlTools
     */
    public function __construct(EntityManagerInterface $entityManager, HtmlTools $htmlTools)
    {
        $this->shopRepository = $entityManager->getRepository(Shop::class);
        $this->regexRepository = $entityManager->getRepository(Regex::class);
        $this->htmlTools = $htmlTools;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:fetchArticles')
            ->addArgument('url')
            ->addArgument('shopId');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shop = $this->shopRepository->find($input->getArgument('shopId'));
        $html = $this->htmlTools->fetchHtmlCode($shop, $input->getArgument('url'));

        preg_match_all(
            $this->regexRepository->getPageContentRegex($shop)[0]->getContentRegex(),
            stripslashes($html->getContent()),
            $matches
        );

        $data = [];

        $homepage = '';
        if (substr($matches[1][0], 0, 4 ) !== "http") {
            $homepage = substr($html->getUrl(), 0, strpos($html->getUrl(), '.lt')) . '.lt';
        }
        for ($i = 0, $iMax = count($matches[1]); $i < $iMax; $i++) {
            $data[] = [
                'img' => $homepage . $matches[1][$i],
                'url' => $homepage . $matches[2][$i],
                'title' => $matches[3][$i],
                'price' => $matches[4][$i],
            ];
        }

        $output->writeln(json_encode($data));
    }
}
