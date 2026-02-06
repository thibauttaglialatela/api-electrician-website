<?php

declare(strict_types=1);

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

final class JWTAuthenticationSuccessListener
{
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data     = $event->getData();
        $response = $event->getResponse();

        $token = $data['token'];

        $response->headers->setCookie(
            Cookie::create(
                name: 'BEARER',
                value: $token,
                path: '/',
                secure: true,
                httpOnly: true,
                sameSite: 'none'
            )
        );

        unset($data['token']);
        $event->setData($data);
    }
}
