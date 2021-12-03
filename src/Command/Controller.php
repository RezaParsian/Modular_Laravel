<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\fileExists;

class Controller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:controller {name} {module} {--r=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create new controller';

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

        $this->line("<fg=yellow>It may take a few second...</>");

        Artisan::call("make:controller " . "../../../modules/{$module}/Controllers/{$name}" . ($this->option("r") != 1 ? " -r" : ""));

        file_put_contents(base_path("modules/{$module}/Controllers/{$name}.php"), str_replace("App\Http\Controllers\..\..\..\m", "M", file_get_contents(base_path("modules/{$module}/Controllers/{$name}.php"))));

        $this->line("<fg=green>controller created successfully.</>");
    }
}
