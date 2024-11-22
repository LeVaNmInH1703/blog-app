<?php

use App\Http\Middleware\LocateMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            LocateMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(function(){
            Log::info(now() );
        })->cron('* * * * *');

        // * * * * *
        // | | | | |
        // | | | | +---- Ngày trong tháng (1 - 31)
        // | | | +------ Tháng (1 - 12 hoặc tên tháng)
        // | | +-------- Ngày trong tuần (0 - 7) (0 và 7 đều đại diện cho Chủ nhật)
        // | +---------- Giờ (0 - 23)
        // +------------ Phút (0 - 59)
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
