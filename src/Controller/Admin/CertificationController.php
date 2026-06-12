<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CertificationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin/certifications')]
final class CertificationController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function showAllCertifications(CertificationRepository $certificationRepository): JsonResponse
    {
        $certifications = $certificationRepository->findAll();

        $jsonCertifications = $this->serializer->serialize($certifications, 'json', ['groups' => 'getCertifications']);


        return new JsonResponse($jsonCertifications, Response::HTTP_OK, [], true);

    }
}
