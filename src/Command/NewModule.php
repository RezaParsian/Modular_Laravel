<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use function PHPUnit\Framework\fileExists;

class NewModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {name}';

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
        $module = $this->argument("name");

        if (!realpath(base_path("modules/{$module}/")))
            mkdir(base_path("modules/{$module}/"));

        if (!realpath(base_path("modules/{$module}/Views/")))
            mkdir(base_path("modules/{$module}/Views/"));

        if (!realpath(base_path("modules/{$module}/Migrations/")))
            mkdir(base_path("modules/{$module}/Migrations/"));

        if (!realpath(base_path("modules/{$module}/Models/")))
            mkdir(base_path("modules/{$module}/Models/"));

        if (!realpath(base_path("modules/{$module}/Controllers/")))
            mkdir(base_path("modules/{$module}/Controllers/"));

        file_put_contents(base_path("modules/{$module}/router.php"), file_get_contents(__DIR__ . "/../tmp/routes.tmp"));
        file_put_contents(base_path("modules/{$module}/composer.json"), file_get_contents(__DIR__ . "/../tmp/composer.tmp"));
        file_put_contents(base_path("modules/{$module}/Views/index.blade.php"), "<h1>This is Rp template</h1>");

        return shell_exec("cd " . base_path("modules/{$module}") . " && composer install");

        echo "add ModulesServiceProvider to config/app.php";
        echo PHP_EOL;
    }
}
