<?php

namespace App\Adapters\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \ReLab\Laravel\Http\Middleware\SecureResponse::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
//             \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'transaction'
        ],

        'api' => [
//            'throttle:60,1',
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
            'bindings',
            'transaction'
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'transaction' => \App\Adapters\Http\Middleware\Transaction::class,
        'client.confirm_login' => \App\Adapters\Http\Middleware\ClientConfirmLogin::class,
        'client.authenticate' => \App\Adapters\Http\Middleware\ClientAuthenticate::class,
        'front.confirm_login' => \App\Adapters\Http\Middleware\FrontConfirmLogin::class,
        'front.authenticate' => \App\Adapters\Http\Middleware\FrontAuthenticate::class,
        'admin.confirm_login' => \App\Adapters\Http\Middleware\AdminConfirmLogin::class,
        'admin.authenticate' => \App\Adapters\Http\Middleware\AdminAuthenticate::class,
        'nocache' => \ReLab\Laravel\Http\Middleware\NoCache::class
    ];

//    /**
//     * The priority-sorted list of middleware.
//     *
//     * This forces non-global middleware to always be in the given order.
//     *
//     * @var array
//     */
//    protected $middlewarePriority = [
//        \Illuminate\Session\Middleware\StartSession::class,
//        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//        \App\Http\Middleware\Authenticate::class,
//        \Illuminate\Session\Middleware\AuthenticateSession::class,
//        \Illuminate\Routing\Middleware\SubstituteBindings::class,
//        \Illuminate\Auth\Middleware\Authorize::class,
//    ];
}
