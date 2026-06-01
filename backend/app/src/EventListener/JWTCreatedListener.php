<?php

namespace App\EventListener;

use App\Entity\AppUser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

final class JWTCreatedListener
{
    public function __invoke(JWTCreatedEvent $event): void
    {

        $user = $event->getUser();

        if (!$user instanceof AppUser) {
            return;
        }

        $payload = $event->getData();

        unset($payload['username'], $payload['email']);

        $payload['id'] = $user->getId();
        $payload['roles'] = $user->getRoles();

        $event->setData($payload);
    }
}
