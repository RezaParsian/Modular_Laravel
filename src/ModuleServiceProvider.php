<?php


namespace Rp76\Module;


use Illuminate\Support\ServiceProvider;
use Rp76\Module\Command\Controller;
use Rp76\Module\Command\Migration;
use Rp76\Module\Command\Model;
use Rp76\Module\Command\Module;
use Rp76\Module\Command\NewModule;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands(Module::class);
        $this->commands(Model::class);
        $this->commands(Migration::class);
        $this->commands(Controller::class);
        $this->commands(NewModule::class);

        $this->publishes([
           realpath(__DIR__."/config/RpModule.php")=>config_path("RpModule.php")
        ],"RpModule");

        $this->publishes([
           realpath(__DIR__."/provider/ModulesProvider.php")=>app_path("Providers/ModulesProvider.php")
        ],"RpModule");
    }

    public function register()
    {

    }
}
