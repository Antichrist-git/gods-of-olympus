<?php

namespace App\Providers;

use App\Models\God;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        // Сравниваем пользователя с владельцем сохранённой записи.
        Gate::define('manage-god', fn (User $user, God $god) =>
            $user->id === (int) $god->getRawOriginal('user_id')
        );
    }
}
