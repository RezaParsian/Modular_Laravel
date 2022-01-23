<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use Rp76\Module\Helper\Command as RpCommand;
use function PHPUnit\Framework\fileExists;

class NewModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make 
    {name : Make New Module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create new module';

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

        $this->line("<fg=yellow>It may take a few second...</>");

        if (!realpath(base_path("modules"))) {
            $this->line("<fg=red>first of all use :</> <fg=yellow>php artisan module:install</>");
            return;
        }

        if (realpath(base_path("modules/{$module}"))) {
            $this->line("<fg=red>this module already exists, choose another name.</>");
            return;
        }

        RpCommand::makePath("modules/{$module}/");

        RpCommand::makePath("modules/{$module}/Views/");

        RpCommand::makePath("modules/{$module}/Migrations/");

        RpCommand::makePath("modules/{$module}/Models/");

        RpCommand::makePath("modules/{$module}/Controllers/");

        RpCommand::makeFile("modules/{$module}/router.php", "{$module}", "routes.tmp");

        RpCommand::makeFile("modules/{$module}/{$module}.php", "{$module}", 'MainClass.tmp');

        RpCommand::makeFile("modules/{$module}/composer.json", "{$module}", "composer.tmp");

        RpCommand::makeFile("modules/{$module}/Views/index.blade.php", "{$module}", "index.blade.tmp");

        RpCommand::exec($module, "composer install");

        RpCommand::add_to_config($module);

        $this->line("<fg=green>Module </><fg=yellow>{$module}</> <fg=green>created successfully.</>");
    }
}
