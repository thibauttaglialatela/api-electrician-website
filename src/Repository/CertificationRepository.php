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
     * @return list<array{id: int, name: string, image_url: string, image_alt: string}>
     */
    public function findAllValidCertifications(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT certification.id id,certification.name name, image.url image_url, image.alt image_alt FROM certification
        LEFT JOIN image ON certification.image_id = image.id
        WHERE (certification.expires_at < :today OR certification.expires_at IS NULL);';
        $today     = new \DateTimeImmutable();
        $resultSet = $conn->executeQuery($sql, ['today' => $today->format('Y-m-d H:i:s')]);

        /** @var list<array{id: int, name: string, image_url: string, image_alt: string}> $results */
        $results = $resultSet->fetchAllAssociative();

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
