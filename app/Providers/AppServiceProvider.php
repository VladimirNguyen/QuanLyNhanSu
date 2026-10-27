<?php

namespace App\Providers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Đảm bảo biến $errors trong mọi view luôn là ViewErrorBag hợp lệ.
        // Các controller cũ dùng Session::push('errors', ...) khiến session('errors')
        // trở thành mảng => $errors->any()/all() trong Blade bị fatal.
        view()->composer('*', function (View $view) {
            $errors = $view->getData()['errors'] ?? null;
            if ($errors instanceof ViewErrorBag) {
                return;
            }
            $bag = new ViewErrorBag();
            $messages = $errors instanceof MessageBag
                ? $errors
                : (is_array($errors) ? $errors : session('errors', []));
            if (is_array($messages)) {
                $messageBag = new MessageBag($messages);
                $bag->put('default', $messageBag);
            }
            $view->with('errors', $bag);
        });
    }
}
