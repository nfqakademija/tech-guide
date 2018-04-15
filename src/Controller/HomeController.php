<?php

namespace App\Controller;

use App\Utils\Guidebot;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(Filesystem $fs, Guidebot $guidebot)
    {
        $json = json_encode($guidebot->makeTriggeringMessages());

        try {
            $fs->dumpFile('../assets/js/data.json', $json);
        }
        catch(IOException $e) {

        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
