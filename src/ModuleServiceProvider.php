<?php


namespace Rp76\Module;


use Illuminate\Support\ServiceProvider;
use Rp76\Module\Command\Migration;
use Rp76\Module\Command\Model;
use Rp76\Module\Command\Module;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands(Module::class);
        $this->commands(Model::class);
        $this->commands(Migration::class);
    }

    public function register()
    {

    }
}
