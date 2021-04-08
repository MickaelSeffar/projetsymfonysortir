<?php



namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\State;
use App\Form\ActivityType;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        // Récupérer id Etat enregistrer=4(En Création) Publier=2(ouvert)
        //$currentState= $entityManager->getRepository(State::class)->find($request->get('idState'));
        $currentState= $entityManager->getRepository(State::class)->find('4');
        $activity->setState($currentState);
        //$activity->setState()
        //$location = new Location();
        //$formLocation = $this->createForm(LocationType::class,$location);
        //$formLocation = $this->createForm($request);
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activity);
            $entityManager->flush();
            $this->addFlash('success', 'Nouvelle sortie');

            return $this->redirectToRoute('activity_view');
        }
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