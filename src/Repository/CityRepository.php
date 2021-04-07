<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    // Function for to get 10 result of city by paging.
    public function getAll($page = 1) {

        $req = $this->createQueryBuilder('city')
            ->where('city.active = :active')->setParameter(':active', true)
            ->orderBy('city.postcode', 'ASC')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10);

        return $req->getQuery()->getResult();

    }
}
