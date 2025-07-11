<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Certification\CertificationDto;
use App\Entity\Certification;
use App\Dto\ImageDto;
use App\Repository\CertificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/certifications', name: 'api_certifications_')]
final class CertificationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function getAllValidCertifications(CertificationRepository $certificationRepository, #[MapQueryParameter] bool $valid, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();

        if (!$valid && (!$user || !\in_array('ROLE_ADMIN', $user->getRoles(), true))) {
            return $this->json([
                'error' => 'Accès non autorisé à la liste complète des certifications',
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        /** @var array<Certification> $certifications */
        $certifications = $valid
            ? $certificationRepository->findAllValidCertifications()
            : $certificationRepository->findAll();

        if (empty($certifications)) {
            return $this->json([
                'certifications' => [],
                'message'        => 'Aucune certification valide',
            ]);
        }

        /** @var array<int, array{id: int, name: string, image_url: string, image_alt: string}> $certifications */
        $validCertifications = array_map(function (array $certifications): CertificationDto {
            return new CertificationDto(
                $certifications['id'],
                $certifications['name'],
                new ImageDto($certifications['image_url'], $certifications['image_alt'])
            );
        }, $certifications);



        return $this->json($validCertifications);
    }
}
