<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateMiddleware extends Authenticate
{

    /**
     *
     * We should try to authenticate when we have a guard
     * in case we do not have a guard we should check if the route exists and is public
     *
     * @param mixed $request
     * @param array $guards
     * @return void
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }
    }
}
