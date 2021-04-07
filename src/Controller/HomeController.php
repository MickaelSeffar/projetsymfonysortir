<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route(path="/accueil", name="home_")
 */
class HomeController extends AbstractController
{

    /**
     * @Route(path="", name="welcome")
     */
    public function home() {
        return $this->render('home/welcome.html.twig');

    }

}