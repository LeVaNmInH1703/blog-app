<?php

namespace App\Providers;

use App\Models\Emoji;
use App\Models\Notification;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // DB::table('sessions')->created(function ($session) {
        //     // Xử lý logic khi session mới được tạo
        //     dump($session);
        //     return $session;
        // });
        //cho phép sử dụng bootstrap 5 cho paginator
        Paginator::useBootstrapFive();
        RateLimiter::for('login',function(Request $request){
            return [
                Limit::perMinute(600),
                Limit::perMinute(6)->by($request->ip()),
                Limit::perMinute(6)->by($request->input('email')),
            ];
        });
        RateLimiter::for('countGroupHasNewMessage',function(Request $request){
            return [
                Limit::perMinute(1000),
                Limit::perMinute(10)->by($request->ip()),
                Limit::perMinute(10)->by($request->input('email')),
            ];
        });
        // đăng ký blade
        Blade::directive('diffForHumans', function ( $time) {
            return "<?php echo strtr((new \Carbon\Carbon($time))->diffForHumans(now()), ['before' => 'ago']) ?>";
        });

        //view composer
        Facades\View::composer(['pages.home','pages.profile','pages.blogDetail'], function (View $view) {
            // Gửi dữ liệu tới view 'app.blade.php'
            $view->with('emojis', Emoji::all());
        });
        Facades\View::composer('layout.header', function ($view) {
            $view->with('notifications',[]);
            $view->with('countNewNotifications','');
        });
    }
}
