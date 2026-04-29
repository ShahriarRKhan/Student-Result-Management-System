<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\NoStore;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'no.store' => NoStore::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('teacher') || $request->is('teacher/*')) {
                return route('teacher.login');
            }

            return route('student.login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::guard('teacher')->check()) {
                return route('teacher.results.index');
            }

            if (Auth::guard('student')->check()) {
                return route('student.index');
            }

            return $request->is('teacher') || $request->is('teacher/*')
                ? route('teacher.login')
                : route('student.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
