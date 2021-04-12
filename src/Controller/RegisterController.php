<?php


namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Register;
use Doctrine\DBAL\Schema\Visitor\AbstractVisitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegisterController
 * @package App\Controller
 * @Route(path="inscription2/", name="register_")
 */
class RegisterController extends AbstractController
{
    /**
     * @Route (path="{activityId}", name="activityRegistration")
     */
    public function activityRegistration(EntityManagerInterface $entityManager, Request $request, $activityId)
    {
        // *** Récuperer l'activité en DB ***
        $activity = $entityManager->getRepository('App:Activity')->find($activityId);
        // *** Récuperer le User connecté en DB ***
        $user = $entityManager->getRepository('App:User')->find($this->getUser());

//        $registrationsNumber = count($activity->getRegistrations());
//        $registrationsMax = $activity->getMaximumUserNumber();

        if (count($activity->getRegistrations()) < $activity->getMaximumUserNumber()) {

            // *** Note pour MICKAEL : Tu avais bien raison, la ligne ci dessous récupère bel et bien le user courant !!!! ***
            // $userconnecte = $this->getUser();

            $activity->setCurrentUserNumber(count($activity->getRegistrations()));

            // *** Création d'une ligne Register, remplissage de celle-ci ***
            $register = new Register();
            $register->setUser($user);
            $register->setActivity($activity);
            $register->setRegisterDate(new \DateTime());
            $register->setActive(true);

            // *** Envoi de l'objet Register en DB, sauvegarde du changement de l'activité en DB ***
            $entityManager->persist($activity);
            $entityManager->persist($register);

            $entityManager->flush();

            // *** Renvoi sur le détail de l'activité ***
            return $this->redirectToRoute("activity_detail", ['id' => $activity->getId()]);
        }




    }

    public function unsubscribe(EntityManagerInterface $entityManager, Request $request, $activityId) {
        $activity = $entityManager->getRepository('App:Activity')->find($activityId);
        $user = $entityManager->getRepository('App:User')->find($this->getUser());
        $registrations = $entityManager->getRepository('App:Register')->getRegistration($activityId);

        $activity->setCurrentUserNumber(count($activity->getRegistrations()));



    }
}