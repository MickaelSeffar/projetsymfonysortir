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
        // Je récupère les données du formulaire et je les range dans un joli tableau
        $infoRecherche=[
            'campusS'=>$form['campus']->getData(),
            'activityNameS'=>$form['activityName']->getData(),
            'managerS'=>$form['manager']->getData(),
            'registeredS'=>$form['registered']->getData(),
            'finishActivityS'=>$form['finishActivity']->getData(),
            'startDateS'=>$form['startDate']->getData(),
            'endDateS'=>$form['endDate']->getData(),
        ];
        // Archivage des activités qui se fait maintenant avec la commande php bin/console app:archive-activity
        //$activityStatus = $entityManager->getRepository('App:Activity')->changeState();
        //foreach ($activityStatus as $activity){
        //    $activity->setActive(false);
        //}
        //$entityManager->flush();

        // Je récupère toutes les activités active
        $activities = $entityManager->getRepository('App:Activity')->getActive();
        // Je les pagine par 10
        $activities = $paginator->paginate($activities,
            $request->query->getInt('page',1),10
        );
        // Je récupère toutes les lignes de la table register
        $registrations = $entityManager->getRepository('App:Register')->findAll();
        // J'envoie le formulaire dans la page acceuil, avec le formulaire, toutes les activitys et et toutes les lignes registers
        return $this->render('home/welcome.html.twig',['searchForm'=>$form->createView(),'activities'=>$activities,'registrations' => $registrations]);

    }

}