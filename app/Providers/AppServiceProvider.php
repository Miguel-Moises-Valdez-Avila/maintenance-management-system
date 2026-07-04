<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

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
        // 🔔 Compartir número de solicitudes NUEVAS (sin adminsAsignados) con el layout
        View::composer('layouts.app', function ($view) {
            $newRequestsCount = 0;

            try {
                /** @var \Google\Cloud\Firestore\FirestoreClient $firestore */
                $firestore = App::make('firebase.firestore');
                $documents = $firestore->collection('requests')->documents();

                foreach ($documents as $doc) {
                    if ($doc->exists()) {
                        $data = $doc->data();
                        $adminsAsignados = $data['adminsAsignados'] ?? [];

                        // Nueva = aún no tiene administrador asignado
                        if (empty($adminsAsignados)) {
                            $newRequestsCount++;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Si Firebase falla, simplemente mostramos 0
                $newRequestsCount = 0;
            }

            $view->with('newRequestsCount', $newRequestsCount);
        });
    }
}

