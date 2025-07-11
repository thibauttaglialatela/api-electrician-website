<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Work;
use App\Enum\ImageUsage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Work>
 */
class WorkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Work::class);
    }

    /**
     * Undocumented function.
     *
     * @return array<int, array{
     * work_id: int,
     * display_name: string,
     * endDate: \DateTimeImmutable,
     * image_url: string,
     * image_alt: string,
     * }>
     */
    public function findLastThreeWorks(int $limit, string $sortField): array
    {
        $allowedSortFields = ['endDate'];
        if (!\in_array($sortField, $allowedSortFields, true)) {
            $sortField = 'endDate';
        }

        $qb = $this->createQueryBuilder('w')
        ->select('w.id AS work_id')
        ->addSelect("COALESCE(c.companyName, CONCAT(c.firstname, ' ', c.lastname)) AS display_name")
        ->addSelect('w.endDate')
        ->addSelect('MIN(i.url) AS image_url')
        ->addSelect('MIN(i.alt) AS image_alt')
        ->join('w.client', 'c')
        ->join('w.illustrations', 'i')
        ->where('i.usageType = :usage')
        ->andWhere('w.endDate IS NOT NULL')
        ->setParameter('usage', ImageUsage::WORK)
        ->groupBy('w.id, w.endDate, c.companyName, c.firstname, c.lastname')
        ->orderBy("w.$sortField", 'DESC')
        ->setMaxResults($limit)
        ;


        /** @var array<int, array{work_id: int, display_name: string, endDate: \DateTimeImmutable, image_url: string, image_alt: string}> */
        $result = $qb->getQuery()->getArrayResult();

        return $result;

    }

    //    /**
    //     * @return Work[] Returns an array of Work objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Work
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
