<?php


namespace App\Service;


use App\Entity\State;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class StateMAJService
{

    protected $entityManager;
    /**
     * StateMAJService constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
    $this->entityManager=$entityManager;
    }

    public function doStateEnCours(){
        // Passage au status "En cours" des activités arrivant à la date de début
        $startActivity = $this->entityManager->getRepository('App:Activity')->startActivity();
        dd($startActivity);
//        $inProgressState = $this->entityManager->getRepository(State::class)->findOneBy(['name'=>'En cours']);
//        foreach ($startActivity as $activity){
//            $activity->setState($inProgressState);
//        }
//        $this->entityManager->flush();
    }


}