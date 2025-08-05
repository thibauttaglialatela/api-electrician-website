<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CertificationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/admin/api/certifications')]
final class CertificationController
{
    #[Route('/', methods: ['GET'])]
    public function showAllCertifications(CertificationRepository $certificationRepository, SerializerInterface $serializer): JsonResponse
    {
        $certifications = $certificationRepository->findAll();

        $jsonCertifications = $serializer->serialize($certifications, 'json', ['groups' => 'getCertifications']);


        return new JsonResponse($jsonCertifications, Response::HTTP_OK, [], true);

    }
}
