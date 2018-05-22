<?php

namespace App\Controller;

use App\Utils\InfluenceCalculator;
use App\Utils\Provider;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\DateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $session->set('api_key', uniqid('', false));

        if (isset($_COOKIE['answers'])) {
            $data = json_decode($_COOKIE['answers'], true);
            foreach ($data as $key => $value) {
                if ((new \DateTime($value['expireTime']))->diff(new \DateTime('now'))->invert
                    === 0
                ) {
                    unset($data[$key]);
                }
            }

            setcookie('answers', json_encode($data));
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }
}
