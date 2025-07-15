<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ClientDto;
use App\Dto\ImageDto;
use App\Dto\Work\LastWorkListDto;
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
}
