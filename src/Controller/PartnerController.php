<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ImageDto;
use App\Dto\Partner\PartnerDto;
use App\Repository\PartnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/partners', name: 'partners_')]
final class PartnerController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function findAllPartners(PartnerRepository $partnerRepository): JsonResponse
    {
        $partners = $partnerRepository->findAllPartners();

        $jsonPartners = array_map(function (array $partner): PartnerDto {
            return new PartnerDto($partner['partner_id'], $partner['name'], $partner['website'], new ImageDto($partner['image_url'], $partner['image_alt']));
        }, $partners);

        return $this->json($jsonPartners);
    }
}
