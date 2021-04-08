<?php



namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
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
        // Récupérer l'état 2
        $

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
    public function detail() {

    }
}