<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Dto\ImageDto;
use App\Dto\Prestation\PrestationDto;
use App\Entity\Image;
use App\Entity\Prestation;
use App\Enum\ImageUsage;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/', methods: ['POST'])]
    public function addOnePrestation(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try {
            $data        = $request->toArray();
            $currentDate = new \DateTimeImmutable('now');

            $prestation = new Prestation();
            $prestation->setName($data['name']);
            $prestation->setPrice((int) $data['tarif']);
            $prestation->setDescription($data['description']);
            $prestation->setCreatedAt($currentDate);

            $image = new Image();
            $image->setUrl($data['url']);
            $image->setAlt($data['alt']);
            $image->setPrestation($prestation);
            $image->setCreatedAt($currentDate);
            $image->setUsageType(ImageUsage::PRESTATION);

            $prestation->setImage($image);

            $entityManager->persist($prestation);
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->json($prestation, 200, [], ['groups' => 'prestation:read']);

        } catch (\Exception $error) {
            return $this->json(['error' => $error->getMessage()], 422);
        }



    }
}
