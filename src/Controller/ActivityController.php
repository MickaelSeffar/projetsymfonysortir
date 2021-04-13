<?php



namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\State;
use App\Form\ActivityType;
use App\Form\CancelType;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ActivityController
 * @package App\Controller
 * @Route(path="activitee/", name="activity_")
 */
class ActivityController extends AbstractController
{

    /**
     * @Route(path="ajouter", name="add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager) {
        $activity = new Activity();
        // Récupérer id Etat enregistrer=8(En Création) Publier=6(ouvert)
        $publishState= $entityManager->getRepository(State::class)->findOneBy(['name'=>'Ouvert']);
        $creationState=$entityManager->getRepository(State::class)->findOneBy(['name'=>'En création']);
        // J'initialise le nombre d'utilisateur à zéro
        $activity->setCurrentUserNumber(0);
        // je créer mon formulaire
        $form = $this->createForm(ActivityType::class, $activity);
        // Je récupère l'utilisateur qui sera l'organisateur de la sortie
        $activity->setManager($this->getUser());
        $activity->setActive(true);
        // J'hydrate
       $form->handleRequest($request);
        // J'enregistre dans ma base de donnée (POST)
        if ($form->isSubmitted() && $form->isValid()) {
               $publier=$form['publier']->getData();
                // Selon l'info que je récupère, je met l'état enregister ou création
                if($publier===true){
                    $activity->setState($publishState);
                    $this->addFlash('success',"L'activité $activity est publiée");
                }else{
                    $activity->setState($creationState);
                    $this->addFlash('success', "L'activité $activity est crée. Elle n'est pas encore publiée");
                }

                $entityManager->persist($activity);
                $entityManager->flush();
                return $this->redirectToRoute('activity_view');
        }
        // GET
        return $this->render('activity/add.html.twig', ['formActivity' => $form->createView()]);
    }
//    /**
//     * @Route(path="", name="view")
//     */
//    public function display(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request) {
//        $activityStatus = $entityManager->getRepository('App:Activity')->changeState();foreach ($activityStatus as $activity){
//            $activity->setActive(false);
//       }
//        $entityManager->flush();
//
//
//        $activities = $entityManager->getRepository('App:Activity')->getActive();
//
//        $activities = $paginator->paginate($activities,
//            $request->query->getInt('page',1),10
//        );
//        $registrations = $entityManager->getRepository('App:Register')->findAll();
//
//        return $this->render('activity/list.html.twig', ['activities' => $activities, 'registrations' => $registrations]);
//    }
    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify(EntityManagerInterface $entityManager,Request $request) {
        // Récupérer id Etat enregistrer=8(En Création) Publier=6(ouvert)
        $publishState= $entityManager->getRepository(State::class)->findOneBy(['name'=>'Ouvert']);
        $creationState=$entityManager->getRepository(State::class)->findOneBy(['name'=>'En création']);
       // Je recupère mon id et monactivité
        $id = $request->get('id');
        $activiteModifier=$entityManager->getRepository('App:Activity')->find($id);
        // Je créer un formulaire et j'y met mon activité
        $form = $this->createForm(ActivityType::class,$activiteModifier );
        // J'hydrate le formulaire
        $form->handleRequest($request);
        // En post
        $publier=$form['publier']->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            // Selon l'info que je récupère, je met l'état enregister ou création
            if($publier===true){
                $activiteModifier->setState($publishState);
                $this->addFlash('success',"L'activité $activiteModifier est modifiée et publiée");
            }else{
                $activiteModifier->setState($creationState);
                $this->addFlash('success', "L'activité $activiteModifier est modifié. Pour la publier, vous devez utilisez le bouton Modifier");
            }
            $entityManager->persist($activiteModifier);
            $entityManager->flush();
            return $this->redirectToRoute('home_welcome');
        }
        // j'affiche le formulaire en get
        return $this->render('activity/edit.html.twig',[
            'editForm'=>$form->createView(),
            'id'=>$id
        ]);
    }
    /**
     * @Route(path="annuler/{id}", name="cancel")
     */
    public function cancel(Request $request,EntityManagerInterface $entityManager) {
        // Récupérer id Etat annuler
        $cancelState= $entityManager->getRepository(State::class)->findOneBy(['name'=>'Fermé']);
        // Je recupère mon id et monactivité
        $id = $request->get('id');
        $activiteSupprimer=$entityManager->getRepository('App:Activity')->find($id);
        // Je créer un formulaire et j'y met mon activité
        $form = $this->createForm(CancelType::class,$activiteSupprimer );
        // J'hydrate le formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reason=$form['cancellationReason']->getData();
            if($reason!=null) {
                $activiteSupprimer->setState($cancelState);
                $entityManager->persist($activiteSupprimer);
                $entityManager->flush();
                $this->addFlash('success', "L'activité $activiteSupprimer est supprimée");
                return $this->redirectToRoute('activity_view');
            }else{
                $this->addFlash('error', "Merci de préciser le motif d'annulation");
                return $this->render('activity/cancel.hml.twig',['cancelFormActivity'=>$form->createView()]);
            }

        }
        // j'affiche le formulaire en get
        return $this->render('activity/cancel.hml.twig',['cancelFormActivity'=>$form->createView()]);
    }

    /**
     * @Route(path="detail/{id}", name="detail")
     */
    public function detail(Request $request, EntityManagerInterface $entityManager) {
        $id = $request->get('id');
        $activity = $entityManager->getRepository('App:Activity')->find($id);
        $users = $entityManager->getRepository('App:Register')->getUserFromRegister($id);

        return $this->render('activity/detail.html.twig', ['activity' => $activity, 'users' => $users]);
    }
    /**
     * @Route(path="publier/{id}", name="publish")
     */
    public function publish(Request $request, EntityManagerInterface $entityManager) {
        // Je récupère l'activité à publier
        $id = $request->get('id');
        $activity = $entityManager->getRepository('App:Activity')->find($id);
        // Récupérer id Etat annuler
        $publishState= $entityManager->getRepository(State::class)->findOneBy(['name'=>'Ouvert']);
        // Je change l'état de mon activité
        $activity->setState($publishState);
        $entityManager->persist($activity);
        $entityManager->flush();
        $this->addFlash('success', "L'activité $activity est publiée");
        return $this->redirectToRoute('home_welcome');
    }

}