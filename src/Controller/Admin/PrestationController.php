<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Dto\ImageDto;
use App\Dto\Prestation\PrestationDto;
use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin/prestations')]
final class PrestationController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function showAllPrestations(PrestationRepository $prestationRepository, SerializerInterface $serializer): JsonResponse
    {
        // récupérer toutes les prestations
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
}
