<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Rp76\Module\Helper\Command as RpCommand;
use function PHPUnit\Framework\fileExists;

class Model extends GeneratorCommand
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
    protected $description = 'create new model';


    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';


    public function handle()
    {
        $name = $this->argument("name");
        $module = $this->argument("module");

        $this->line("<fg=yellow>It may take a few second...</>");

        Artisan::call("make:model " . "../../modules/{$module}/Models/{$name}" . ($this->option("m") != 1 ? " -m" : ""));

        file_put_contents(base_path("modules/{$module}/Models/{$name}.php"), str_replace("App\Models\..\..\m", "M", file_get_contents(base_path("modules/{$module}/Models/{$name}.php"))));

        if ($this->option("m") != 1) {
            $files = glob("database/migrations/*" . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', "create_{$name}")) . "*");
            $files = end($files);
            $fileName = explode("/", $files);
            rename($files, base_path("modules/{$module}/Migrations/" . end($fileName)));
        }

        $this->line("<fg=green>model created successfully.</>");
    }

    protected function getStub()
    {
        return $this->option('pivot')
            ? $this->resolveStubPath('/stubs/model.pivot.stub')
            : $this->resolveStubPath('/stubs/model.stub');
    }

    protected function resolveStubPath($stub)
    {
        $stubPath = str_replace("/ControllerMakeCommand.php", "", (new \ReflectionClass(ModelMakeCommand::class))->getFileName()) . $stub;

        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : $stubPath;
    }

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }
}
