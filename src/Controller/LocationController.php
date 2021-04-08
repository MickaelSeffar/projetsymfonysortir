<?php



namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LocationController
 * @package App\Controller
 * @Route(path="lieu/", name="location_")
 */
class LocationController extends AbstractController
{

    /**
     * @Route(path="ajouter", name="add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager) {
        $location = new Location();

        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();
            $this->addFlash('success', 'Nouveau lieu');

            return $this->redirectToRoute('location_view');
        }
        return $this->render('activity/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(path="", name="view")
     */
    public function display(EntityManagerInterface $entityManager) {
        $locations = $entityManager->getRepository('App:Location')->findAll();

        return $this->render('location/list.html.twig', ['locations' => $locations]);
    }

}