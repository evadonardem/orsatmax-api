<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dingo\Api\Transformer\Adapter\Fractal;
use League\Fractal\Manager;
use App\Api\V1\Serializers\CustomJsonSerializer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $fractal = new Manager();
            $fractal->setSerializer(new CustomJsonSerializer());
            return new Fractal($fractal);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
