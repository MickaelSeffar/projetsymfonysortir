<?php

namespace App\Repository;

use App\Entity\Activity;
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


    public function changeState(){
        $date = new \DateTime('-30 days');
        $req = $this->createQueryBuilder('activity')
            ->select('activity')
            ->where('activity.beginDateTime < :date')
            ->andWhere('activity.active = true')
            ->setParameter(':date',$date);

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
