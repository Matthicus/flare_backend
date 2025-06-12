<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class SameSiteCookieFix
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        foreach ($response->headers->getCookies() as $cookie) {
            $response->headers->setCookie(
                new Cookie(
                    $cookie->getName(),
                    $cookie->getValue(),
                    $cookie->getExpiresTime(),
                    $cookie->getPath(),
                    $cookie->getDomain(),
                    $cookie->isSecure(),
                    $cookie->isHttpOnly(),
                    false, // raw
                    'None' // <<< this is the key line
                )
            );
        }

        return $response;
    }
}
