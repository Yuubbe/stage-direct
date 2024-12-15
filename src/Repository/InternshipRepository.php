<?php

namespace App\Repository;

use App\Entity\Internship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Internship>
 */
class InternshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Internship::class);
    }

    /**
     * @return Internship[] Returns an array of Internship objects
     */    
    public function findAll(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit; // Calcul de l'offset
    
        return $this->createQueryBuilder('i')
            ->orderBy('i.id', 'ASC')  // Trier par ID ou tout autre champ
            ->setFirstResult($offset)  // Définir l'offset
            ->setMaxResults($limit)   // Limiter le nombre de résultats par page
            ->getQuery()
            ->getResult();
    }

    public function searchBy($criteria): ?array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.title LIKE :val')
            ->setParameter('val', '%' . $criteria['title'] . '%')
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Internship[] Returns an array of Internship objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Internship
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}