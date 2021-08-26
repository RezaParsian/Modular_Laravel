<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\fileExists;

class Model extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:model {name} {module} {--m=1}';

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
        $name = $this->argument("name");
        $module = $this->argument("module");

        Artisan::call("make:model " . "../../modules/{$module}/Models/{$name}" . ($this->option("m") != 1 ? " -m" : ""));

        file_put_contents(realpath("modules/{$module}/Models/{$name}.php"), str_replace("App\Models\..\..\m", "M", file_get_contents(realpath("modules/{$module}/Models/{$name}.php"))));

        if ($this->option("m") != 1) {
            $files = glob("database/migrations/*" . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)) . "*");
            print_r($files);
            shell_exec("mv " . $files[0] . " " . realpath("modules/{$module}/Migrations/"));
        }

        echo "Your model made";
        echo PHP_EOL;
    }
}
