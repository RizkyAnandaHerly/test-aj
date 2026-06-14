<?php

namespace App\Providers;

use App\Models\Inbound;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductLocation;
use App\Models\QcInspection;
use App\Models\QcParameter;
use App\Models\Vendor;
use App\Models\Warehouse;
use App\Observers\ActivityLogObserver;
use Illuminate\Support\ServiceProvider;

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
     *
     * Register ActivityLogObserver on every model that should be audited.
     * No controller changes needed — the observer fires automatically.
     */
    public function boot(): void
    {
        $observer = ActivityLogObserver::class;

        Product::observe($observer);
        Inbound::observe($observer);
        Location::observe($observer);
        ProductLocation::observe($observer);
        QcInspection::observe($observer);
        QcParameter::observe($observer);
        Vendor::observe($observer);
        Warehouse::observe($observer);
    }
}
