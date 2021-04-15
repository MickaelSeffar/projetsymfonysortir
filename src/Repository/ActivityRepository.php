<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }


    public function getActive(){
        $req = $this->createQueryBuilder('activity')
            ->where('activity.active = :active')->setParameter(':active',true);
        return $req->getQuery()->getResult();
    }
    public function search($infoRecherche){
        $req = $this->createQueryBuilder('a')
            ->select('a');
        $req->where('a.active = :active')
            ->setParameter(':active',true);
        if(!empty($infoRecherche['activityNameS'])){
            $req->andwhere('a.name LIKE :nom')
                ->setParameter(':nom','%'.$infoRecherche['activityNameS'].'%');
        }
        if(!empty($infoRecherche['startDateS'])) {
            $req->andwhere('a.beginDateTime BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $infoRecherche['startDateS'])
                ->setParameter('dateFin', $infoRecherche['endDateS']);
        }
        if(!empty( $infoRecherche['campusS'])) {
            $req->andWhere('a.campus =:campus')
                ->setParameter(':campus', $infoRecherche['campusS']);
        }
        if ($infoRecherche['managerS'] === true) {
            //dd($infoRecherche['userConnecte']);
            $req->andWhere('a.manager = :user')
                ->setParameter(':user', $infoRecherche['userConnecte']);
        }
        $dateJour=new \DateTime();
        if($infoRecherche['finishActivityS']===true) {
            $req->andWhere('a.beginDateTime < :date')
                ->setParameter(':date', $dateJour);
        }
        if($infoRecherche['registeredS']===true&&$infoRecherche['registeredNot']===true){
            //  $this->addFlash('error', "Merci de préciser le motif d'annulation");
        }else {
            if ($infoRecherche['registeredS'] === true) {
                $req->join('a.registrations', 'r')
                    ->andWhere('r.user =:user')
                    ->setParameter(':user', $infoRecherche['userConnecte']);
            }
            if ($infoRecherche['registeredNot'] === true) {
                $req->join('a.registrations', 'r')
                    ->andWhere('r.user <>:user')
                    ->setParameter(':user', $infoRecherche['userConnecte']);
            }
        }

        return $req->getQuery()->getResult();
    }

    public function archiveActivity(){
        $archiveDate = new \DateTime('-30 days');

        $req = $this->createQueryBuilder('activity')
            ->select('activity')
            ->where('activity.beginDateTime < :archiveDate')
            ->andWhere('activity.active = true')
            ->setParameter(':archiveDate',$archiveDate);

        return $req->getQuery()->getResult();

    }

    public function closeActivity(){
        $closeDate = new \DateTime('now');

        $req = $this->createQueryBuilder('activity')
            ->select('activity')
            ->where('activity.registrationDeadline < :closeDate' )
            ->setParameter(':closeDate', $closeDate);

        return $req->getQuery()->getResult();

    }
    public function startActivity(){
        // Objectif Mettre les activité commencées et pas terminées en statut en cours
        // Je paramètre ma date du jour et heure du jour
        $maintenant = new \DateTime('now');
        // $dureeActivite = new \DateTime('activity.beginDateTime + activity.duration');
        // Je recherche toutes les activités dont la date de début est inférieur à la date du jour
        // et qui sont active
        $req = $this->createQueryBuilder('activity')
            ->select('activity')
            ->where('activity.beginDateTime < :startDate')
            ->andWhere('activity.active= true')
            ->setParameter(':startDate',$maintenant);
        $result = $req->getQuery()->getResult();
        // Je parcours ces résultats, pour chaque activité je calcul la dateheure de fin
        // je regarde si cette date est dans le futur
        // je rassemble les activités sélectionnées dans un tableau
        $resultTab[]=null;
        foreach ($result as $activity) {
            $id=$activity->getId();
            $duration=$activity->getDuration();
            $beginTime=$activity->getBeginDateTime();
            // Je rajoute le temps de l'activité
            $endate=$beginTime->modify("+{$duration} minutes");
            $activity->setRegistrationDeadLine($endate);
            // dd($endate);
            // J'affiche
            //dd($activity);
            if($endate>=$maintenant){
                $resultTab[]=$activity;
            }
        }
        return $resultTab;
    }



    public function cancelActivity($id) {
        $req = $this->createQueryBuilder('activity')
            ->select('activity')
            ->where('activity.id = :id')
            ->setParameter(':id', $id);
        return $req->getQuery()->getResult();
    }


    // /**
    //  * @return Activity[] Returns an array of Activity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Activity
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getUserFromRegister($id) {
        $req = $this->createQueryBuilder('activity')
            ->select('activity.registrations')
            ->join('activity.user')
            ->where('activity.id = :id')->setParameter(':id', $id);

        return $req->getQuery()->getResult();
    }

    public function getRegistrations($id) {
        $req = $this->createQueryBuilder('activity')
            ->select('activity.registrations')
            ->where('activity.registrations = false');

        return $req->getQuery()->getResult();
    }
}
