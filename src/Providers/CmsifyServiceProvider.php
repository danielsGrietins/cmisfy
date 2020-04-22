<?php

namespace Cmsify\Cmsify\Providers;

use Cmsify\Cmsify\Http\Middleware\AdminAuthMiddleware;
use Illuminate\Support\ServiceProvider;

class CmsifyServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/cmsify.php' => config_path('cmsify.php'),
        ], 'cmsify');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        config([
            'auth.guards' => array_merge(config('auth.guards'), [
                'cmsify-api' => [
                    'driver'   => 'jwt',
                    'provider' => 'users',
                ],
            ])
        ]);
        $this->loadMigrations();
        $this->app['router']->aliasMiddleware('admin-auth', AdminAuthMiddleware::class);
    }

    /**
     * @return void
     */
    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
