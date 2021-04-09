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
    public function activityRegistration(EntityManagerInterface $entityManager, $activityId,$userId)
    {

        //$id = $request->get('id');
        $activity = $entityManager->getRepository('App:Activity')->find($activityId);
        $user = $entityManager->getRepository('App:User')->find($userId);
       // $userconnecte = $this->getUser();

        $register = new Register();
        $register->setUser($user);
        $register->setActivity($activity);

        $register->setRegisterDate(new \DateTime());

        $register->setActive(true);

        $entityManager->persist($activity);
        $entityManager->persist($register);

        $entityManager->flush();


        return $this->redirectToRoute("activity_detail", ['id' => $activity->getId()]);

        //récupérer le nombre d'inscrit
        /* $nbinscrit = $activity->getCurrentUserNumber();
         //Récupérer le nombre maximum d'utilisateur
         $maxinscrit = $activity->getMaximumUserNumber();

         if ($nbinscrit<$maxinscrit){

         }
        */


    }
}