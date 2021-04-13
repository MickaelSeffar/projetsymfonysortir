<?php

namespace App\Repository;

use App\Entity\Register;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Register|null find($id, $lockMode = null, $lockVersion = null)
 * @method Register|null findOneBy(array $criteria, array $orderBy = null)
 * @method Register[]    findAll()
 * @method Register[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegisterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Register::class);
    }

    // /**
    //  * @return Register[] Returns an array of Register objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Register
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getUserFromRegister($id) {
        $req = $this->createQueryBuilder('reg')
            ->join('reg.activity', 'act')
            ->join('reg.user', 'usr')
            ->select('usr')
            ->addSelect('reg')
            ->where('act.id = :id')->setParameter(':id', $id)
            ->andWhere('reg.active = true');

        return $req->getQuery()->getResult();
    }

    public function getRegistration($id) {
        $req = $this->createQueryBuilder('register')
            ->select('register')
            ->where('register.activity = :id')->setParameter(':id', $id)
            ->andWhere('register.active = true');

        return $req->getQuery()->getResult();
    }
    public function getRegistrationUnsubscribed($idActivity, $idUser) {
        $req = $this->createQueryBuilder('register')
            ->select('register')
            ->where('register.activity = :idActivity')->setParameter(':idActivity', $idActivity)
            ->andWhere('register.active = true')
            ->andWhere('register.user = :idUser')->setParameter('idUser', $idUser);

        return $req->getQuery()->getOneOrNullResult();
    }
}
