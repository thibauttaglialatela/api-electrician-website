<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/prestations', name: 'prestations_')]
final class PrestationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function getAllPrestations(PrestationRepository $prestationRepository, SerializerInterface $serializer): JsonResponse
    {
        $prestations        = $prestationRepository->findAll();
        $jsonPrestationList = $serializer->serialize($prestations, 'json', ['groups' => 'prestations:list']);

        return JsonResponse::fromJsonString($jsonPrestationList);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function getOnePrestation(?Prestation $prestation, SerializerInterface $serializer): JsonResponse
    {
        if (null === $prestation) {
            return $this->json(['error' => 'Cette prestation n\' existe pas'], JsonResponse::HTTP_NOT_FOUND);
        }

        $jsonPrestationDetail = $serializer->serialize($prestation, 'json', ['groups' => ['prestations:list', 'prestations:detail']]);

        return JsonResponse::fromJsonString($jsonPrestationDetail);
    }
}
