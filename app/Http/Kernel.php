<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        // Гараж 2.0
        'owner.active' => \App\Http\Middleware\EnsureOwnerIsActive::class,
        'owns.listing' => \App\Http\Middleware\EnsureOwnsListing::class,
    ];

    protected $routeMiddleware = [
        // 'parse-login' => ParseLoginMiddleware::class,
        // 'qr-scan' => \App\Http\Middleware\QRScanMiddleware::class,
        // 'test-api' => \App\Http\Middleware\TestApiMiddleware::class,
        // 'auth' => \App\Http\Middleware\Authenticate::class,
        // 'no-access' => \App\Http\Middleware\AccessMiddleware::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'swagger.docs.auth' => \App\Http\Middleware\SwaggerDocsAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
       // 'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    //     'permission' => \App\Http\Middleware\PermissionMiddleware::class,
    //    // 'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
    //     'api_key'   =>  \App\Http\Middleware\ApiKeyMiddleware::class,
    //     'arzon_key' =>  \App\Http\Middleware\ArzonKeyMiddleware::class,

        // 'apikey' => AstriksApiKey::class,
        // 'block_update_history_save' => BlockUpdateHistory::class,
        // 'provider_auth' => \App\Http\Middleware\AuthBasicMiddleware::class,

        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
        // 'log-payment' => \App\Http\Middleware\LogPaymentProvider::class,
        // 'access-route' => \App\Http\Middleware\RouteAccessRights::class,

        // 'auth-key' => \App\Http\Middleware\AuthPackages::class,
        // 'access-operator' => \App\Http\Middleware\AccessOperatorRouteMiddleware::class,
        // 'sms-api-key' => \App\Http\Middleware\ApiSmsKeyMiddleware::class,
        // 'api-log' => \App\Http\Middleware\LogApiRefactoringMiddleware::class,
    ];
}
