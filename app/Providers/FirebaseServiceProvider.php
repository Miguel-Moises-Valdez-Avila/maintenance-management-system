<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));

        // Auth
        $this->app->singleton('firebase.auth', function () use ($factory) {
            return $factory->createAuth();
        });

        // Firestore
        $this->app->singleton('firebase.firestore', function () use ($factory) {
            return $factory->createFirestore()->database();
        });

        // Realtime Database
        $this->app->singleton('firebase.database', function () use ($factory) {
            return $factory->createDatabase();
        });
    }

    public function boot(): void {}
}
