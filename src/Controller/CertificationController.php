<?php

declare(strict_types=1);

namespace App\Controller;

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

        $certifications = $valid
            ? $certificationRepository->findAllValidCertifications()
            : $certificationRepository->findAll();

        if (empty($certifications)) {
            return $this->json([
                'certifications' => [],
                'message'        => 'Aucune certification valide',
            ]);
        }

        $jsonContent = $serializer->serialize($certifications, 'json', ['groups' => ['certification:read', 'image:read']]);

        return JsonResponse::fromJsonString($jsonContent);
    }
}
