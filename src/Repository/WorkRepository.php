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

    /**
     * Undocumented function.
     *
     * @return array<int, array{
     * work_id: int,
     * display_name: string,
     * end_date: \DateTimeImmutable,
     * image_url: string,
     * image_alt: string}>
     */
    public function findAllWorks(): array
    {

        $qb = $this->createQueryBuilder('w')
        ->select('w.id AS work_id')
        ->addSelect("COALESCE(c.companyName, CONCAT(c.firstname, ' ', c.lastname)) AS display_name")
        ->addSelect('w.endDate AS end_date')
        ->addSelect('MIN(i.url) AS image_url')
        ->addSelect('MIN(i.alt) AS image_alt')
        ->join('w.client', 'c')
        ->leftJoin('w.illustrations', 'i')
        ->where('i.usageType = :usage')
        ->andWhere('w.endDate IS NOT NULL')
        ->setParameter('usage', ImageUsage::WORK)
        ->groupBy('w.id, w.endDate, c.companyName, c.firstname, c.lastname')
        ->orderBy('w.endDate', 'DESC')
        ;

        /** @var array<int, array{work_id: int, display_name: string, end_date: \DateTimeImmutable, image_url: string, image_alt: string}> */
        $result = $qb->getQuery()->getArrayResult();

        return $result;
    }

    /**
     * Undocumented function.
     *
     *@return array{
     * data: array<int, array{
     * work_id: int,
     * display_name: string,
     * end_date: \DateTimeImmutable,
     * image_url: string,
     * image_alt: string
     * }>,
     * total: int,
     * page: int,
     * limit: int
     * }
     */
    public function paginateWorks(int $page = 1, int $limit = 3): array
    {

        $qb = $this->createQueryBuilder('w')
        ->select('w.id AS work_id')
        ->addSelect("COALESCE(c.companyName, CONCAT(c.firstname, ' ', c.lastname)) AS display_name")
                ->addSelect('w.endDate AS end_date')
        ->addSelect('MIN(i.url) AS image_url')
        ->addSelect('MIN(i.alt) AS image_alt')
        ->join('w.client', 'c')
        ->leftJoin('w.illustrations', 'i')
                ->where('i.usageType = :usage')
        ->andWhere('w.endDate IS NOT NULL')
        ->setParameter('usage', ImageUsage::WORK)
                ->groupBy('w.id, w.endDate, c.companyName, c.firstname, c.lastname')
        ->orderBy('w.endDate', 'DESC')
        ->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit)
        ;

        /** @var array<int, array{work_id: int, display_name: string, end_date: \DateTimeImmutable, image_url: string, image_alt: string}> */
        $data = $qb->getQuery()->getArrayResult();

        // Compter le nombre total de résultat
        $countQb = $this->createQueryBuilder('w')
        ->select('COUNT(w.id)')
        ->where('w.endDate IS NOT NULL');

        $total = (int) $countQb->getQuery()->getSingleScalarResult();

        return [
            'data'  => $data,
            'total' => $total,
            'page'  => $page,
            'limit' => $limit,
        ];
    }

    /*     SELECT w.id, w.description, w.start_date, w.end_date,
    COALESCE(client.company_name, CONCAT(client.salutation, ' ', client.firstname, ' ', client.lastname)) AS display_name, image.url AS image_url, image.alt AS image_alt
             FROM work w
             INNER JOIN client ON w.client_id = client.id
             LEFT JOIN image on image.work_id = w.id
             WHERE w.id = 1; */

    /**
     * Undocumented function.
     *
     * @return array<int, array{
     * work_id: int,
     * description: string,
     * startDate: \DateTimeImmutable,
     * endDate: \DateTimeImmutable|null,
     * display_name: string,
     * image_url: string,
     * image_alt: string}>
     */
    public function findWorkById(int $id): array
    {
        $qb = $this->createQueryBuilder('w')
        ->select('w.id AS work_id')
        ->addSelect('w.description')
        ->addSelect('w.startDate')
        ->addSelect('w.endDate')
        ->addSelect("COALESCE(c.companyName, CONCAT(c.salutation, ' ', c.firstname, ' ', c.lastname)) AS display_name")
        ->addSelect('i.url AS image_url')
        ->addSelect('i.alt AS image_alt')
        ->join('w.client', 'c')
        ->leftJoin('w.illustrations', 'i')
        ->where('w.id = :id')
        ->setParameter('id', $id);

        /** @var array<int, array{work_id: int, description: string, startDate: \DateTimeImmutable, endDate: \DateTimeImmutable|null, display_name: string, image_url: string, image_alt: string}> */
        $results = $qb->getQuery()->getArrayResult();

        return $results;
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
