<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ModulesProvider extends ServiceProvider
{

    public function boot()
    {
        $modules = config("RpModule");

        foreach ($modules as $module) {

            if (file_exists(base_path() . '/' . $module . '/' . $module . '.php'))
                include base_path() . '/' . $module . '/' . $module . '.php';

            if (file_exists(base_path() . '/' . $module . '/router.php'))
                include base_path() . '/' . $module . "/router.php";

            $classname = "Module\\" . $module;
            new $classname();

            if (is_dir(base_path() . '/' . $module . '/Views'))
                $this->loadViewsFrom(base_path() . '/' . $module . '/Views', $module);

            if (is_dir(base_path() . '/' . $module . '/Migrations'))
                $this->loadMigrationsFrom(base_path() . '/' . $module . '/Migrations');
        }
    }

    public function register()
    {

    }
}
