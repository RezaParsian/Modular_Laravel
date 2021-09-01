<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use function PHPUnit\Framework\fileExists;

class Migration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migration {name} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create new migration';

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
        $name = $this->argument("name");
        $module = $this->argument("module");

        Artisan::call("make:migration {$name} --path=modules/{$module}/Migrations/");

        $this->line("<fg=yellow>It may take a few second...</>");
        sleep(1);
        $this->line("<fg=green>migration created successfully.</>");
    }
}
