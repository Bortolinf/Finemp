<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Ability;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // cria uma permissao chamada 'is-admin'
        Gate::define('is-admin', function($user){
            return $user->admin === 1;
        });
        
        // codigo novo 
        Gate::before(function ($user, $ability) {
            if ($user->abilities()->contains($ability)) {
                return true;   
            } else
            {
               // caso a ability ainda nao existe já cria ela no sistema
                Ability::firstOrCreate([ 'name' => $ability]);
               // usuario admin pode TUDO!
                return $user->admin === 1;
            }
        });
    }
}
