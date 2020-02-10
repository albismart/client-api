<?php

namespace App\Providers;

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
      $scanHelperFiles = glob(base_path().'/app/Helpers/*.php');
      foreach ($scanHelperFiles as $helpersFile) {
          include_once $helpersFile;
      }
    }
}
