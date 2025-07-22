<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ClientDto;
use App\Dto\ImageDto;
use App\Dto\Work\DetailWorkDto;
use App\Dto\Work\LastWorkListDto;
use App\Exception\NotFoundApiException;
use App\Repository\ImageRepository;
use App\Repository\WorkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/works', name: 'works_')]
final class WorkController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function findAllWorks(WorkRepository $workRepository): JsonResponse
    {
        $works = $workRepository->findAllWorks();

        $jsonWorksList = array_map(function (array $works): LastWorkListDto {
            return new LastWorkListDto(
                $works['work_id'],
                new ClientDto($works['display_name']),
                $works['end_date'],
                new ImageDto($works['image_url'], $works['image_alt'])
            );
        }, $works);

        return $this->json($jsonWorksList);
    }

    #[Route('/latest', name: 'last_works', methods: ['GET'])]
    public function findLastCompletedWorks(
        WorkRepository $workRepository,
        #[MapQueryParameter] string $limit,
        #[MapQueryParameter] string $sort = 'endDate',
    ): JsonResponse {
        $limit = (int) $limit;

        $works = $workRepository->findLastThreeWorks($limit, $sort);

        if (empty($works)) {
            return $this->json([
                'Chantiers' => [],
                'message'   => 'Aucun chantier achevé',
            ]);
        }



        $dto = array_map(function (array $works): LastWorkListDto {
            return new LastWorkListDto(
                $works['work_id'],
                new ClientDto($works['display_name']),
                $works['endDate'],
                new ImageDto($works['image_url'], $works['image_alt'])
            );
        }, $works);


        return $this->json($dto);
    }

    #[Route('/{workId}', name: 'show', methods: ['GET'])]
    public function findOneWork(WorkRepository $workRepository, ImageRepository $imageRepository, int $workId): JsonResponse
    {
        $results = $workRepository->findWorkById($workId);


        if ([] === $results) {
            throw new NotFoundApiException('chantier');
        }

        if (($results[0]['endDate'] < $results[0]['startDate']) || null === $results[0]['endDate']) {
            $durationDays = 0;
        } else {
            $durationDays = (int) date_diff($results[0]['startDate'], $results[0]['endDate'])->days;
        }

        $images = [];
        foreach ($results as $key => $work) {
            $imageDto = new ImageDto($work['image_url'], $work['image_alt']);
            array_push($images, $imageDto);
        }

        $jsonWorkDetail = new DetailWorkDto(
            $results[0]['work_id'],
            new ClientDto($results[0]['display_name']),
            $results[0]['description'],
            $results[0]['startDate'],
            $results[0]['endDate'],
            $durationDays,
            $images
        );

        return $this->json($jsonWorkDetail);
    }
}
