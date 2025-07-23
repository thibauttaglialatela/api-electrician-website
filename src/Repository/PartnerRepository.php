<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Partner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partner>
 */
class PartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partner::class);
    }

    /**
     * Undocumented function.
     *
     * @return array<int, array{
     * partner_id: int,
     * name: string,
     * website: string,
     * image_url: string,
     * image_alt: string}>
     */
    public function findAllPartners(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT partner.id partner_id, partner.name AS name, partner.website_url AS website, image.url AS image_url, image.alt AS image_alt
                FROM partner
                LEFT JOIN image ON image.id = partner.image_id';

        try {
            $resultSet = $conn->executeQuery($sql);

            /** @var array<int, array{partner_id: int, name: string, website: string, image_url: string, image_alt: string}> */
            $result = $resultSet->fetchAllAssociative();

            return $result;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erreur SQL : ' . $e->getMessage());
        }

    }

    //    /**
    //     * @return Partner[] Returns an array of Partner objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Partner
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
