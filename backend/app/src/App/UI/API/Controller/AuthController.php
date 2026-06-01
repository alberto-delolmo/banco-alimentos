<?php

namespace App\App\UI\API\Controller;

use App\Entity\AppUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController
{
    #[Route('/api/auth/me', name: 'api_auth_me', methods: ['GET'])]
    public function myProfile(Security $security): JsonResponse
    {
        $user = $security->getUser();

        if (!$user instanceof AppUser) {
            return new JsonResponse([
                'message' => 'Usuario no autenticado.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'firstSurname' => $user->getFirstSurname(),
            'secondSurname' => $user->getSecondSurname(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}
