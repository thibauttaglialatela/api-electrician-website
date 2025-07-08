<?php

declare(strict_types=1);

namespace App\Controller;

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

        $jsonWorkList = $serializer->serialize($works, 'json', ['groups' => 'work:list']);

        return JsonResponse::fromJsonString($jsonWorkList);
    }
}
