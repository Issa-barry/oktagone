<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

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
        // Déclare tes scopes (exemples – adapte à ton besoin)
        Passport::tokensCan([
            'pos:order.create'    => 'Créer des ventes',
            'pos:product.manage'  => 'Gérer le catalogue',
            'transfer:read'       => 'Lire les transferts',
            'transfer:write'      => 'Écrire sur les transferts',
            'fx:portfolio.read'   => 'Voir les portefeuilles',
            'fx:order.execute'    => 'Passer des ordres',
        ]);

        // Durées de vie
        Passport::tokensExpireIn(now()->addMinutes(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // (option) activer le Password Grant pour tests seulement
        // Passport::enablePasswordGrant();

        //  register : 
        RateLimiter::for('register', function ($request) {
            $ip = $request->ip();
            $email = (string) $request->input('email');
            return [
                //avec message d'erreur
                // Limit::perMinute(5)->by($ip)->response(function () {
                //     return response()->json([
                //         'success' => false,
                //         'message' => 'Trop de tentatives d’inscription. Réessaie dans une minute.',
                //     ], 429);
                // }),
                Limit::perMinute(5)->by($ip),
                Limit::perMinute(5)->by($email ?: $ip),
            ];
        });
    }
}
