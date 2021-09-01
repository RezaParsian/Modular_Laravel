<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ModulesProvider extends ServiceProvider
{

    public function boot()
    {
        $modules = config("RpModule");

        foreach ($modules as $module) {
            if (file_exists(base_path() . '/modules/' . $module . '/' . $module . '.php'))
                require base_path() . '/modules/' . $module . '/' . $module . '.php';

            if (file_exists(base_path() . '/modules/' .  $module . "/router.php"))
                include base_path() . '/modules/' .  $module . "/router.php";

            $classname = "Modules\\" . $module . "\\" . $module;
            new $classname();

            if (is_dir(base_path() . '/modules/' . $module . '/Views'))
                $this->loadViewsFrom(base_path() . '/modules/' .  $module . '/Views', $module);

            if (is_dir(base_path() . '/modules/' . $module . '/Migrations'))
                $this->loadMigrationsFrom(base_path() . '/modules/' . $module . '/Migrations');
        }
    }

    public function register()
    {

    }
}
