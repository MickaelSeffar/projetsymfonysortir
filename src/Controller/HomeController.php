<?php


namespace App\Controller;

use App\Entity\Activity;
use App\Form\SearchActivityType;
use App\Service\StateMAJService;
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
        // J'appelle le service MAJ des Encours
        $stateMAJService->doStateEnCours();

        //J'appelle le service d'archivage des activités
        $stateMAJService->archiveActivities();

        //J'appelle le service de fermeture des activités
        $stateMAJService->closeActivities();
        $activityTest=new Activity();
        // Je créer un formulaire
        $form = $this->createForm(SearchActivityType::class,$activityTest );
        // J'hydrate le formulaire
        $form->handleRequest($request);
        // Je récupère les données du formulaire et je les range dans un joli tableau
        if(!empty($this->getUser())) {
            $userconnecte = $this->getUser();
        }else {
            $userconnecte = null;
        }
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
        if($infoRecherche['registeredS']===true&&$infoRecherche['registeredNot']===true){
            $this->addFlash('error', "Il faut choisir entre inscrit/e ou non inscrit/e");
        }
        $activityfound= $entityManager->getRepository('App:Activity')->search($infoRecherche);
        // je pagine
        $activityfound = $paginator->paginate($activityfound,
            $request->query->getInt('page',1),10
        );
        return $this->render('home/welcome.html.twig',[
            'searchForm'=>$form->createView(),
            'activities'=>$activityfound,
        ]);
    }






}