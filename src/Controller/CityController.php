<?php



namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CityController
 * @package App\Controller
 * @Route(path="ville/", name="city_")
 */
class CityController extends AbstractController
{



    /**
     * @Route(path="", name="view", methods={"GET"})
     */
    public function display(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator) {
        // Get all cities in DB, by page.
        $cities = $entityManager->getRepository('App:City')->getAll();
        $cities = $paginator->paginate($cities,
            $request->query->getInt('page',1),10
        );
        // Display the result in the TWIG page.
        return $this->render('city/list.html.twig', ['cities' => $cities]);
    }


    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify(Request $request, EntityManagerInterface $entityManager) {
        $id = $request->get('id');
        $city = $entityManager->getRepository('App:City')->find($id);

        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('success', 'Modification ville');

            return $this->redirectToRoute('city_view');
        }
        return $this->render('city/modify.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route(path="ajouter", name="add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager) {
        $city = new City();
        $city->setActive(true);

        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('success', 'Nouvelle ville');

            return $this->redirectToRoute('city_view');
        }
        return $this->render('city/create.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route(path="supprimer/{id}", name="delete")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager) {
        $id = $request->get('id');
        $city = $entityManager->getRepository('App:City')->find($id);

        if ($city) {
            $city->setActive(false);
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('success', 'Suppression ville');

            return $this->redirectToRoute('city_view');
        } else {
            $this->addFlash('error', 'Suppression impossible');
            return $this->redirectToRoute('city_view');
        }
    }

}