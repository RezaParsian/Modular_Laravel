<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use function PHPUnit\Framework\fileExists;

class Module extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install dependency';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->changeComposer();

        $this->makeMainFolder();

        $this->makeMainService();

        if (!realpath(base_path("modules/Rp76/")))
            mkdir(base_path("modules/Rp76/"));

        if (!realpath(base_path("modules/Rp76/Views/")))
            mkdir(base_path("modules/Rp76/Views/"));

        if (!realpath(base_path("modules/Rp76/Migrations/")))
            mkdir(base_path("modules/Rp76/Migrations/"));

        if (!realpath(base_path("modules/Rp76/Models/")))
            mkdir(base_path("modules/Rp76/Models/"));

        if (!realpath(base_path("modules/Rp76/Controllers/")))
            mkdir(base_path("modules/Rp76/Controllers/"));

        file_put_contents(base_path("modules/Rp76/router.php"),file_get_contents(__DIR__."/../tmp/routes.tmp"));
        file_put_contents(base_path("modules/Rp76/Views/index.blade.php"),"<h1>This is Rp template</h1>");

        echo "add ModulesServiceProvider to config/app.php";
        echo PHP_EOL;
    }

    public function changeComposer(): void
    {
        $composer = json_decode(file_get_contents(base_path("composer.json")), true);
        $composer['autoload']['psr-4']['Modules\\'] = "modules/";
        file_put_contents(base_path("composer.json"), str_replace('\/', "/", json_encode($composer)));
    }

    public function makeMainFolder(): void
    {
        if (!realpath(base_path("modules/")))
            mkdir(base_path("modules/"));
    }

    public function makeMainService(): void
    {
        copy(__DIR__ . "/../tmp/ModulesServiceProvider.tmp", base_path("modules/ModulesServiceProvider.php"));
    }
}
