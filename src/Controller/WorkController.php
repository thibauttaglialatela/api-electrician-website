<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Work\ClientDto;
use App\Dto\Work\ImageDto;
use App\Dto\Work\LastWorkListDto;
use App\Repository\WorkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/works', name: 'works_')]
final class WorkController extends AbstractController
{
    #[Route('/', name: 'last_works', methods: ['GET'])]
    public function findLastCompletedWorks(
        WorkRepository $workRepository,
        SerializerInterface $serializer,
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
                date_format($works['endDate'], 'd/m/Y'),
                new ImageDto($works['image_url'], $works['image_alt'])
            );
        }, $works);


        return $this->json($dto);
    }
}
