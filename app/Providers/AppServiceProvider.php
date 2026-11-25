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
        Gate::define('manage-archive', fn (User $user) => $user->is_admin);

        // Администратор может управлять карточками всех владельцев.
        Gate::define('manage-god', fn (User $user, God $god) =>
            $user->is_admin || $user->id === (int) $god->getRawOriginal('user_id')
        );
    }
}
