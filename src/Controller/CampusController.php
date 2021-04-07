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

        return $this->render('campus/campusManagement.html.twig', ['campus'=>$campus]);
    }

    /**
     * @Route(path="modifier/{id}", name="modify")
     */
    public function modify() {

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
    public function delete() {

    }

}