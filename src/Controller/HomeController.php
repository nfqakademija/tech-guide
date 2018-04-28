<?php

namespace App\Controller;

use App\Utils\InfluenceCalculator;
use App\Utils\Provider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        //$device = new InfluenceCalculator([31, 1, 2, 2, 2], $entityManager);
        //$provider = new Provider([40, 1, 3, 2, 2], $entityManager);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            //'data' => $provider->makeUrls()
        ]);
    }
}
