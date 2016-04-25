<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
//        \App\Http\Middleware\EncryptCookies::class,
        \App\Http\Middleware\CORS::class,
//        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//        \Illuminate\Session\Middleware\StartSession::class,
//        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
//        'auth' => \App\Http\Middleware\Authenticate::class,
//        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
//        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'user_owner' => \App\Http\Middleware\UserOwner::class,
        'exercise_owner' => \App\Http\Middleware\ExerciseOwner::class,
        'jwt.auth' => \App\Http\Middleware\JWTGetUserFromToken::class,
        'jwt.refresh' => \App\Http\Middleware\JWTRefreshToken::class,
        'acl' => \App\Http\Middleware\AclHasPermission::class,
    ];
}
