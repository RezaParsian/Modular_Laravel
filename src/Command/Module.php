<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use Rp76\Module\Helper\Command as RpCommand;

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
    protected $description = 'install module dependency (use it once)';

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
        if (realpath(base_path("modules"))) {
            $this->line("<fg=red>the module installed before.</>");
            return;
        }

        RpCommand::makePath('modules/');

        RpCommand::makePath("modules/Rp76/");

        RpCommand::makePath('modules/Rp76/Views/');

        RpCommand::makePath('modules/Rp76/Migrations/');

        RpCommand::makePath('modules/Rp76/Models/');

        RpCommand::makePath('modules/Rp76/Controllers/');

        RpCommand::makeFile("modules/Rp76/router.php", "Rp76", "routes.tmp");

        RpCommand::makeFile("modules/Rp76/Rp76.php", "Rp76", 'MainClass.tmp');

        RpCommand::makeFile("modules/Rp76/composer.json", "Rp76", "composer.tmp");

        RpCommand::makeFile("modules/Rp76/Views/index.blade.php", "Rp76", "index.blade.tmp");

        RpCommand::exec("Rp76", "composer install");

        $this->line("<fg=yellow>It may take a few second...</>");
        sleep(1);
        $this->line("<fg=green>Module install successfully.</>");
    }
}
