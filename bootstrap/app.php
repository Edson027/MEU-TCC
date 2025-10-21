<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
/*
protected function schedule(Schedule $schedule)
{
    $schedule->command('stock:check-low')->daily();
}
    

/*
protected function schedule(Schedule $schedule)
{
    $schedule->command('medicines:check')->dailyAt('08:00'); // Executa diariamente às 8h
}

protected function schedule(Schedule $schedule)
{
    $schedule->command('stock:check')->daily(); // Verifica diariamente
}*/