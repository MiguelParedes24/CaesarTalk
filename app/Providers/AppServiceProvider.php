<?php

namespace App\Providers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        /*     $this->app->booted(function () {
            View::composer('*', function ($view) {
                $unreadMessagesCount = 0;

                if (Auth::check()) {
                    $unreadMessagesCount = Message::where('receiver_id', Auth::id())
                        ->where('is_read', 0)
                        ->count();
                }

                $view->with('unreadMessagesCount', $unreadMessagesCount);
            });
        }); */
    }
}
