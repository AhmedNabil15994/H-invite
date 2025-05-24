<?php

namespace Modules\Party\Providers;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Core\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    protected $module_name = 'Party';

    protected $frontend_routes = [
        'parties.php',
    ];
    protected $dashboard_routes = [
        'parties.php',
        'invitations.php',
    ];

    protected $vendor_routes = [
        'parties.php',
    ];

    protected $api_routes = [
        'parties.php',
        'invitations.php',
    ];

    protected function frontendGroups(){

        return [
            'middleware' => config('core.route-middleware.frontend.guest'),
            'prefix' => LaravelLocalization::setLocale() . config('core.route-prefix.frontend')
        ];
    }

    protected function dashboardGroups(){

        return [
            'middleware' => config('core.route-middleware.dashboard.auth'),
            'prefix' => LaravelLocalization::setLocale() . config('core.route-prefix.dashboard')
        ];
    }

    protected function vendorGroups()
    {

        return [
            'middleware' => config('core.route-middleware.vendor.auth'),
            'prefix' => LaravelLocalization::setLocale() . config('core.route-prefix.vendor')
        ];
    }

    protected function apiGroups(){

        return [
            'middleware' => config('core.route-middleware.api.guest'),
            'prefix' => config('core.route-prefix.api')
        ];
    }
}
