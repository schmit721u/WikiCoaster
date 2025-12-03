<?php

namespace App\Repository;

use App\Entity\Coaster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coaster>
 */
class CoasterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coaster::class);
    }

    public function findFiltered(
        int $parkId = 0,
        int $categorieId = 0,
        int $count = 2,
        int $page = 1,
    ): Paginator
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.park', 'p')
            ->leftJoin('c.categories', 'ca')
        ;

        if ($parkId > 0) {
            $qb->andWhere('p.id = :parkId')
                ->setParameter('parkId', $parkId);
        }

        if ($categorieId > 0) {
            $qb->andWhere('ca.id = :catId')
                ->setParameter('catId', $categorieId);
        }

        $begin = ($page - 1) * $count; // Calcul de l'offset
        $qb->setFirstResult($begin) // OFFSET
            ->setMaxResults($count); // LIMIT

        return new Paginator($qb->getQuery());
    }

    //    /**
    //     * @return Coaster[] Returns an array of Coaster objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Coaster
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}