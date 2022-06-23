<?php

namespace App\Repository;

use App\Entity\Chambre;
use App\Filter\ChambreFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chambre>
 *
 * @method Chambre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chambre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chambre[]    findAll()
 * @method Chambre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chambre::class);
    }

    public function add(Chambre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Chambre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }




    public function filter(ChambreFilter $filter)
    {
        $query = $this->createQueryBuilder("c")
            ->leftJoin("c.detailsCommandes", "d")
        ;

            if($filter->to  && $filter->from )
            {
                $query = $query
                    ->andWhere('d.startAt > :to')
                    ->orWhere('d.endAt < :from')
                    ->orWhere('d.startAt is null')
                    ->orWhere('d.endAt is null')
                    ->setParameter('to' , $filter->to)
                    ->setParameter('from' , $filter->from)
                ;   
            } 

        return $query
        ->getQuery()
        ->getResult()
        ;
    }

    public function filterById(ChambreFilter $filter, $id)
    {
        $query = $this->createQueryBuilder("c")
            ->andWhere('c.id = :id')
            ->leftJoin("c.detailsCommandes", "d")
        ;

            if($filter->to  && $filter->from )
            {
                $query = $query
                    ->andWhere('d.startAt > :to')
                    ->orWhere('d.endAt < :from')
                    ->orWhere('d.startAt is null')
                    ->orWhere('d.endAt is null')
                    ->setParameter('id' , $id)
                    ->setParameter('to' , $filter->to)
                    ->setParameter('from' , $filter->from)
                ;   
            } 

        return $query
        ->getQuery()
        ->getResult()
        ;
    }



//    /**
//     * @return Chambre[] Returns an array of Chambre objects
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

//    public function findOneBySomeField($value): ?Chambre
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}