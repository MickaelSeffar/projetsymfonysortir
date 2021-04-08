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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function edit(Request $request,UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler,ValidatorInterface $validator) {
        // Récupération du User connecté
        $userconnecte=$this->getUser();
        // Je l'ai place dans les champs
        $form = $this->createForm(EditUserType::class, $userconnecte);
        // J'hydrate le formulaire
        $form->handleRequest($request);
        $errors=$validator->validate($userconnecte);
        // en post je tombe dans le if
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userconnecte);
            $entityManager->flush();
            // Je rajoute un message flash de succès
            $this->addFlash('success','Vos modifications ont bien été prises en compte');
        }
        // En get
        if(count($errors)>0){
           $this>$this->addFlash('error',(string)$errors[0]);
        }

        return $this->render('user/edit.html.twig',[
            'editForm' => $form->createView(),

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