<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Paginator::defaultView('pagination::bootstrap-4');

        Gate::define('destroy-item', function (User $user, Item $item) {
            return $user->is_admin OR $item->price < 1000;
        });

        Gate::define('create-category', function (User $user) {
            return true;
        });
    }
}
