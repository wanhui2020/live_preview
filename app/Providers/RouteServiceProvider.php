<?php

namespace App\Providers;

use File;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $this->registerRouteFromDirectory(
            base_path('routes/api'),
            $this->namespace."\\Api",
            'api',
            ['api']
        );
    }

    protected function registerRouteFromDirectory(
        $path,
        $namespace,
        $prefix = '',
        array $middlewares = []
    ) {
        foreach (File::allFiles($path) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            Route::middleware($middlewares)
                ->prefix($prefix)
                ->namespace($namespace)
                ->group($file->getPathname());
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $this->registerRouteFromDirectory(
            base_path('routes/web'),
            $this->namespace,
            '',
            ['web']
        );
    }
}
