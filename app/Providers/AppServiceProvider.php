<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
    }
}
