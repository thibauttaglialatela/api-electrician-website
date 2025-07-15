<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ImageDto;
use App\Dto\Prestation\PrestationDetailDto;
use App\Dto\Prestation\PrestationDto;
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
        $prestations = $prestationRepository->findAll();

        $jsonPrestationList = array_filter(array_map(function (Prestation $prestation): ?PrestationDto {
            $image = $prestation->getImage();

            if (
                null    === $prestation->getId()
                || null === $prestation->getName()
                || null === $prestation->getPrice()
                || null === $image
                || null === $image->getUrl()
                || null === $image->getAlt()
            ) {
                return null;
            }

            return new PrestationDto(
                $prestation->getId(),
                $prestation->getName(),
                $prestation->getPrice(),
                new ImageDto($image->getUrl(), $image->getAlt())
            );
        }, $prestations));

        return $this->json($jsonPrestationList);

    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function getOnePrestation(?Prestation $prestation, SerializerInterface $serializer): JsonResponse
    {
        if (null === $prestation) {
            return $this->json(['error' => 'Cette prestation n\' existe pas'], JsonResponse::HTTP_NOT_FOUND);
        }

        $image = $prestation->getImage();

        if (
            null === $prestation->getName()
            || !\is_int($prestation->getPrice())
            || !\is_string($prestation->getDescription())
            || (null === $image)
            || (null === $image->getUrl())
            || (null === $image->getAlt())
        ) {
            return $this->json(['error' => 'Données incorrecte'], 422);
        }

        $jsonPrestationDetail = new PrestationDetailDto(
            $prestation->getName(),
            $prestation->getPrice(),
            $prestation->getDescription(),
            new ImageDto($image->getUrl(), $image->getAlt())
        );

        return $this->json($jsonPrestationDetail);

    }
}
