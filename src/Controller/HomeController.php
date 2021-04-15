<?php


namespace App\Controller;

use App\Entity\State;
use App\Entity\Activity;
use App\Form\ActivityType;
use App\Form\SearchActivityType;
use App\Service\StateMAJService;
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
    public function home(Request $request, EntityManagerInterface $entityManager,PaginatorInterface  $paginator, StateMAJService $stateMAJService) {
        // Je récupère toutes les lignes de la table register
        //$registrations = $entityManager->getRepository('App:Register')->findAll();
        $activityTest=new Activity();
        // Je créer un formulaire
        $form = $this->createForm(SearchActivityType::class,$activityTest );
        // J'hydrate le formulaire
        $form->handleRequest($request);

        // Archivage des activités qui se fait maintenant avec la commande php bin/console app:archive-activity
        $activityStatus = $entityManager->getRepository('App:Activity')->archiveActivity();
        foreach ($activityStatus as $activity){
            $activity->setActive(false);
        }
        $entityManager->flush();

        // Fermeture des activités
       $closeActivity = $entityManager->getRepository('App:Activity')->closeActivity();

        $closeState= $entityManager->getRepository(State::class)->findOneBy(['name'=>'Fermé']);
        foreach ($closeActivity as $activity){
            $activity->setState($closeState);
        }

        $entityManager->flush();
        $userconnecte=$this->getUser();

        // J'appelle le service MAJ des Encours
        //$stateMAJService->doStateEnCours();

        // Si le formulaire est activé
        if ($form->isSubmitted()) {
         // Je récupère les données du formulaire et je les range dans un joli tableau
            $infoRecherche=[
             'campusS'=>$form['campusName']->getData(),
             'activityNameS'=>$form['activityName']->getData(),
             'managerS'=>$form['managerB']->getData(),
                'registeredNot'=>$form['registeredActivity']->getData(),
             'registeredS'=>$form['registered']->getData(),
             'finishActivityS'=>$form['finishActivity']->getData(),
             'startDateS'=>$form['startDate']->getData(),
             'endDateS'=>$form['endDate']->getData(),
                'userConnecte'=>$userconnecte,
            ];
            $activityfound= $entityManager->getRepository('App:Activity')->search($infoRecherche);
            // je pagine
            $activityfound = $paginator->paginate($activityfound,
               $request->query->getInt('page',1),10
            );

            return $this->render('home/welcome.html.twig',[
                'searchForm'=>$form->createView(),
                'activities'=>$activityfound,
                'test'=>$infoRecherche['campusS'] ]);
        }


        // Je récupère toutes les activités active
        $activities = $entityManager->getRepository('App:Activity')->getActive();
        // Je les pagine par 10
        $activities = $paginator->paginate($activities,
            $request->query->getInt('page',1),10
        );
        $test='rien';
        // J'envoie le formulaire dans la page acceuil, avec le formulaire, toutes les activitys et et toutes les lignes registers
        return $this->render('home/welcome.html.twig',['searchForm'=>$form->createView(),'activities'=>$activities]);

    }

}