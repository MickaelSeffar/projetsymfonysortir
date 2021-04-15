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
        $this->entityManager = $entityManager;
    }

    public function doStateEnCours()
    {
        // Passage au status "En cours" des activités arrivant à la date de début
        $startActivity = $this->entityManager->getRepository('App:Activity')->startActivity();
        $inProgressState = $this->entityManager->getRepository(State::class)->findOneBy(['name'=>'En cours']);
       // dd($inProgressState);
       // dd($startActivity);

        foreach ($startActivity as $activity){
            if(!empty($activity)){
            $activity->setState($inProgressState);
            $this->entityManager->flush();
            }
        }

    }

    public function archiveActivities()
    {
        // Archivage des activités qui se fait maintenant avec la commande php bin/console app:archive-activity
        $activityStatus = $this->entityManager->getRepository('App:Activity')->archiveActivity();
        foreach ($activityStatus as $activity){
            $activity->setActive(false);
        }
        $this->entityManager->flush();
    }

    public function closeActivities(){
        // Fermeture des activités
        $closeActivity = $this->entityManager->getRepository('App:Activity')->closeActivity();

        $closeState= $this->entityManager->getRepository(State::class)->findOneBy(['name'=>'Fermé']);
        foreach ($closeActivity as $activity){
            $activity->setState($closeState);
        }

        $this->entityManager->flush();
    }
}