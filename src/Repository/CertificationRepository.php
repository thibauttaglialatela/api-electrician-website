<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Certification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Certification>
 */
class CertificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certification::class);
    }

    /**
     * @return Certification[]
     */
    public function findAllValidCertifications(): array
    {
        /** @var Certification[] $results */
        $results = $this->createQueryBuilder('c')
            ->where('c.expiresAt > :today')
            ->orWhere('c.expiresAt IS NULL')
            ->setParameter('today', new \DateTimeImmutable('today'))
            ->orderBy('c.expiresAt', 'ASC')
            ->getQuery()
            ->getResult();

        return $results;
    }

    //    /**
    //     * @return Certification[] Returns an array of Certification objects
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

    //    public function findOneBySomeField($value): ?Certification
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
