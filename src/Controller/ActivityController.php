<?php



namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\State;
use App\Form\ActivityType;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
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
        $creationState= $entityManager->getRepository(State::class)->findOneBy(['name'=>'En cours']);
        $publishState=$entityManager->getRepository(State::class)->findOneBy(['name'=>'En création']);
        // J'initialise le nombre d'utilisateur à zéro
        $activity->setCurrentUserNumber(0);
        // je créer mon formulaire
        $form = $this->createForm(ActivityType::class, $activity);
        // Je récupère l'utilisateur qui sera l'organisateur de la sortie
        $activity->setManager($this->getUser());
        // J'hydrate
       $form->handleRequest($request);
        // Si le formulaire est submit, je récupère l'information de la chekbox publier pour définir l'état
//        if($form->isSubmitted()){
//            $publier=$form['publier']->getData();
//           // Selon l'info que je récupère, je met l'état enregister ou création
//          if($publier===true){
//                $activity->setState($publishState);
//                $this->addFlash('success',"L'activité $activity est publiée");
//           }else{
//              $activity->setState($creationState);
//             $this->addFlash('success',"L'activité $activity est enregistrée. Pour la publier, vous devez utilisez le bouton Modifier");
//            }
       //}
        // J'enregistre dans ma base de donnée (POST)
        if ($form->isSubmitted() && $form->isValid()) {
               $publier=$form['publier']->getData();
                // Selon l'info que je récupère, je met l'état enregister ou création
                if($publier===true){
                    $activity->setState($publishState);
                    $this->addFlash('success',"L'activité $activity est publiée");
                }else{
                    $activity->setState($creationState);
                    $this->addFlash('success', "L'activité $activity est enregistrée. Pour la publier, vous devez utilisez le bouton Modifier");
                }

                $entityManager->persist($activity);
                $entityManager->flush();
                return $this->redirectToRoute('activity_view');
        }
        // GET
        return $this->render('activity/add.html.twig', ['formActivity' => $form->createView()]);
    }
    /**
     * @Route(path="", name="view")
     */
    public function display(EntityManagerInterface $entityManager) {
        $activities = $entityManager->getRepository('App:Activity')->findAll();
        $registrations = $entityManager->getRepository('App:Register')->findAll();

        return $this->render('activity/list.html.twig', ['activities' => $activities, 'registrations' => $registrations]);
    }
    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify(EntityManagerInterface $entityManager) {
        $activiteModifier=$entityManager->getRepository('App:Activity')->find(id);
        $form = $this->createForm(ActivityType::class,$activiteModifier );

    }
    /**
     * @Route(path="annuler/{id}", name="cancel")
     */
    public function cancel() {

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
}