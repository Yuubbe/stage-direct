<?php

namespace App\Repository;

use App\Entity\InternshipReportEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InternshipReportEntity>
 */
class InternshipReportEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InternshipReportEntity::class);
    }

    /**
     * @return InternshipReportEntity[] Returns an array of InternshipReportEntity objects
     */
    public function findByInternship($internshipId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.internship = :internshipId')
            ->setParameter('internshipId', $internshipId)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return InternshipReportEntity|null Returns a single report by its ID
     */
    public function findOneById($id): ?InternshipReportEntity
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return InternshipReportEntity[] Returns an array of InternshipReportEntity objects
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

    //    public function findOneBySomeField($value): ?InternshipReportEntity
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
