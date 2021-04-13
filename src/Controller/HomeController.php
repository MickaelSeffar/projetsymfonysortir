<?php


namespace App\Controller;

use App\Form\ActivityType;
use App\Form\SearchActivityType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function home(Request $request, EntityManagerInterface $entityManager,PaginatorInterface  $paginator) {
        // Je créer un formulaire
        $form = $this->createForm(SearchActivityType::class );
        // J'hydrate le formulaire
        $form->handleRequest($request);

        // Archivage des activités qui se fait maintenant avec la commande php bin/console app:archive-activity
        //$activityStatus = $entityManager->getRepository('App:Activity')->changeState();
        //foreach ($activityStatus as $activity){
        //    $activity->setActive(false);
        //}
        //$entityManager->flush();


        $activities = $entityManager->getRepository('App:Activity')->getActive();

        $activities = $paginator->paginate($activities,
            $request->query->getInt('page',1),10
        );
        $registrations = $entityManager->getRepository('App:Register')->findAll();

        return $this->render('home/welcome.html.twig',['searchForm'=>$form->createView(),'activities'=>$activities,'registrations' => $registrations]);

    }

}