<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/contact', name: 'contact_')]
final class ContactController extends AbstractController
{
    #[Route('/receive', name: '_receive', methods: ['POST'])]
    public function receiveMessageFromCustomer(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $json = $request->getContent();

        /* @var Contact $contact */
        try {
            $contact = $serializer->deserialize($json, Contact::class, 'json');
        } catch (\Throwable $e) {
            return throw new BadRequestException('Données invalides ou mal formées');
        }

        // validation des données
        $errors = $validator->validate($contact);
        if (\count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorMessages], 422);
        }

        $em->persist($contact);
        $em->flush();

        return $this->json(['Message envoyé'], 201);
    }
}
