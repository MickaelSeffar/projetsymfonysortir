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
        $creationState= $entityManager->getRepository(State::class)->find('8');
        $publishState=$entityManager->getRepository(State::class)->find('6');
        // je créer mon formulaire
        $form = $this->createForm(ActivityType::class, $activity);
        $activity->setState($creationState);
        // Mettre le manager

        // J'hydrate
        $form->handleRequest($request);
        // Si c'est le bouton publier qui est cliqué
        if ($form->get('publier')->isClicked()) {
            $activity->setState($publishState);
        }
        // J'enregistre dans ma base de donnée (POST)
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activity);
            $entityManager->flush();
            $this->addFlash('success', 'Nouvelle sortie');
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

        return $this->render('activity/list.html.twig', ['activities' => $activities]);
    }
    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify() {

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

        return $this->render('activity/detail.html.twig', ['activity' => $activity]);
    }
}