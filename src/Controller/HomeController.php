<?php


namespace App\Controller;

use App\Form\ActivityType;
use App\Form\SearchActivityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function home(Request $request) {
        // Je créer un formulaire
        $form = $this->createForm(SearchActivityType::class );
        // J'hydrate le formulaire
        $form->handleRequest($request);

        return $this->render('home/welcome.html.twig',['searchForm'=>$form->createView()]);

    }

}