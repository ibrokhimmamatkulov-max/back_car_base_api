<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SwaggerDocsAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        $expectedUsername = config('l5-swagger.docs_auth.username');
        $expectedPassword = config('l5-swagger.docs_auth.password');

        if (
            $username === null
            || $password === null
            || ! hash_equals((string) $expectedUsername, (string) $username)
            || ! hash_equals((string) $expectedPassword, (string) $password)
        ) {
            return response('Unauthorized.', 401, [
                'WWW-Authenticate' => 'Basic realm="API Docs"',
            ]);
        }

        return $next($request);
    }
}
