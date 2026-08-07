<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Dto\ImageDto;
use App\Dto\Prestation\PrestationDetailDto;
use App\Dto\Prestation\PrestationDto;
use App\Entity\Image;
use App\Entity\Prestation;
use App\Enum\ImageUsage;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/delete/{id}', methods: ['DELETE'])]
    public function deleteOnePrestation(?Prestation $prestation, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$prestation) {
            return $this->json(['status' => 404, 'error' => 'Not found', 'message' => 'Cette prestation n\'existe pas'], 404);
        }

        try {
            $entityManager->remove($prestation);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(), 'message' => 'Une erreur inattendue est survenue. Veuillez réessayer plus tard.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/{id}', methods: ['GET'])]
    public function getOnePrestation(?Prestation $prestation): JsonResponse
    {
        if (!$prestation) {
            return $this->json(['status' => 404, 'error' => 'Not found', 'message' => 'Cette prestation n\'existe pas'], 404);
        }

        $image = $prestation->getImage();
        if (!$image) {
            return $this->json(['status' => 404, 'error' => 'Not found', 'message' => 'L\'image de cette prestation est manquante'], 404);
        }

        $id          = $prestation->getId();
        $name        = $prestation->getName();
        $price       = $prestation->getPrice();
        $description = $prestation->getDescription();
        $url         = $image->getUrl();
        $alt         = $image->getAlt();

        if (null === $id || null === $name || null === $price || null === $description || null === $url || null === $alt) {
            return $this->json(['status' => 500, 'error' => 'Internal Error', 'message' => 'Données de la prestation incomplètes'], 500);
        }

        $jsonPrestationDetail = new PrestationDetailDto(
            $id,
            $name,
            $price,
            $description,
            new ImageDto($url, $alt)
        );

        return $this->json($jsonPrestationDetail);
    }

    #[Route('/edit/{id}', methods: ['PUT', 'POST'])]
    public function editPrestation(?Prestation $prestation, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$prestation) {
            return $this->json([
                'status'  => 404,
                'error'   => 'Not found',
                'message' => 'Cette prestation n\'existe pas',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!\is_array($data)) {
            return $this->json([
                'status'  => 400,
                'error'   => 'Bad Request',
                'message' => 'Format JSON invalide',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['name']) && \is_string($data['name'])) {
            $prestation->setName($data['name']);
        }

        if (isset($data['tarif']) && (\is_int($data['tarif']) || is_numeric($data['tarif']))) {
            $prestation->setPrice((int) $data['tarif']);
        }

        if (isset($data['description']) && \is_string($data['description'])) {
            $prestation->setDescription($data['description']);
        }

        $image = $prestation->getImage();
        if ($image) {
            if (isset($data['urlImage']) && \is_string($data['urlImage'])) {
                $image->setUrl($data['urlImage']);
            }
            if (isset($data['altImage']) && \is_string($data['altImage'])) {
                $image->setAlt($data['altImage']);
            }
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Prestation modifiée avec succès',
        ], Response::HTTP_OK);
    }
}
