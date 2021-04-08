<?php



namespace App\Controller;


use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * Class UserController
 * @package App\Controller
 * @Route(path="profil/", name="user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route(path="modifier", name="edit")
     */
    public function edit(Request $request,UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler) {
        // Récupération du User connecté
        $userconnecte=$this->getUser();
        // Je l'ai place dans les champs
        $form = $this->createForm(EditUserType::class, $userconnecte);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $userconnecte->setPassword(
                $passwordEncoder->encodePassword(
                    $userconnecte,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userconnecte);
            $entityManager->flush();
            return $this->render('user/edit.html.twig', [
                'editForm' => $form->createView()
            ]);
        }

        return $this->render('user/edit.html.twig',[
            'editForm' => $form->createView()
        ]);
     }
    /**
     * @Route(path="{id}", name="profil")
     */
    public function display(Request $request, EntityManagerInterface $entityManager) {

        return $this->render('user/display.html.twig');
            }
    /**
     * @Route(path="connexion", name="connection")
     */
    public function connection() {

    }


}