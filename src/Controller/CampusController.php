<?php



namespace App\Controller;


use App\Entity\Campus;
use App\Form\CampusType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CampusController
 * @package App\Controller
 * @Route(path="campus/", name="campus_")
 */
class CampusController extends AbstractController
{

    /**
     * @Route(path="", name="view", methods={"GET"})
     */
    public function display(EntityManagerInterface $entityManager) {

        $campus = $entityManager->getRepository('App:Campus')->getAll('1');

        return $this->render('campus/list.twig', ['campus'=>$campus]);
    }

    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify(Request $request, EntityManagerInterface $entityManager) {

        $id = $request->get('id');
        $campus = $entityManager->getRepository('App:Campus')->find($id);

        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();
            $this->addFlash('success', 'Modification campus');

            return $this->redirectToRoute('campus_view');
        }

        return $this->render('campus/modify.html.twig',[
            'campusForm'=>$form->createView()]);

    }
    /**
     * @Route(path="ajouter", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em) {

        $campus = new Campus();
        $campus->setActive(true);
        $campusForm = $this->createForm(CampusType::class,$campus);

        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted()){
            $em->persist($campus);
            $em->flush();

            $this->addFlash('success', 'Campus ajouté !');
            return  $this->redirectToRoute('campus_view',['name'=>$campus->getName()]);
        }


        return $this->render('campus/add.html.twig', [
            'campusForm'=>$campusForm->createView()
        ]);

    }


    /**
     * @Route(path="supprimer/{id}", name="delete")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager) {
        $id = $request->get('id');
        $campus = $entityManager->getRepository('App:Campus')->find($id);

        if ($campus){
            $campus->setActive(false);
            $entityManager->persist($campus);
            $entityManager->flush();
            $this->addFlash('success', 'Suppression campus');

            return $this->redirectToRoute('campus_view');
        } else {
            $this->addFlash('error', 'Suppression impossible');
            return $this->redirectToRoute('city_view');
        }

    }

}