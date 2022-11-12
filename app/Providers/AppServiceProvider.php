<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Spatie\QueryString\QueryString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(QueryString::class, function () {
            $request = $this->app->get(Request::class);

            return new QueryString(urldecode($request->getRequestUri()));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
