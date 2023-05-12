<?php

namespace App\Providers;

use App\Models\Taller;
use App\Models\Usuari;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->admin || $user->superadmin;
        });

        Gate::define('isSuperAdmin', function ($user) {
            return $user->superadmin;
        });

        Gate::define('isCreator', function ($user, $taller) {
            return $user->email == $taller->creador;
        });

        Gate::define('isCreatorOrAdmin', function ($user, $taller) {
            return $user->email == $taller->creador || $user->admin || $user->superadmin;
        });
    
        Gate::define('isParticipantOrAboveLimit', function($user, $taller) {
            return $taller->participants != NULL && (collect(explode(', ', $taller->participants))->contains($user->email) || ($taller->nalumnes <= collect(explode(', ', $taller->participants))->count() - 1));
        });

        Gate::define('isAboveLimit', function($user,$taller) {
            return $taller->participants != NULL && ($taller->nalumnes <= collect(explode(', ', $taller->participants))->count() - 1);
        });
        
        
        Gate::define('isParticipant', function($user, $taller) {
            return $taller->participants != NULL && collect(explode(', ', $taller->participants))->contains($user->email);
        });

        Gate::define('isAjudant', function($user){
            return Taller::getTallersByAjudant($user->email)->count() > 0;
        });
        // comprobem si el usuari s'ha apuntat al maxim de 3 tallers
        Gate::define('maximParticipacio', function($user) {
            return Usuari::participacio($user) >= 3;
        });

        // comprobem si el usuari esta participan en algun taller
        Gate::define('isParticipantofAnyTaller', function($user) {
            return Usuari::participacio($user) > 0;
        });
        // Comprobem que el usuari no hagi creat ningun taller
        Gate::define('isCreator', function($user){
            return Usuari::getCreadors($user) > 0;
        });

        Gate::define('isParticipantEmail',function($user, $email, $taller){
            return $taller->participants != NULL && collect(explode(', ', $taller->participants))->contains($email);
        });

    }
}
