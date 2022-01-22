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
use Symfony\Component\Console\Input\InputOption;
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

//        Artisan::call("make:model " . "../../modules/{$module}/Models/{$name}" . ($this->option("m") != 1 ? " -m" : ""));
//
//        file_put_contents(base_path("modules/{$module}/Models/{$name}.php"), str_replace("App\Models\..\..\m", "M", file_get_contents(base_path("modules/{$module}/Models/{$name}.php"))));
//
//        if ($this->option("m") != 1) {
//            $files = glob("database/migrations/*" . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', "create_{$name}")) . "*");
//            $files = end($files);
//            $fileName = explode("/", $files);
//            rename($files, base_path("modules/{$module}/Migrations/" . end($fileName)));
//        }

        $this->files->makeDirectory("modules/{$module}/Models", 0777, true, true);
        $this->files->put(base_path("modules/{$module}/Models/{$name}.php"), $this->sortImports($this->buildClass($name)));

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
        $stubPath = str_replace("/ModelMakeCommand.php", "", (new \ReflectionClass(ModelMakeCommand::class))->getFileName()) . $stub;

        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : $stubPath;
    }

    private function addOptions()
    {
        $options = [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, policy, and resource controller for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['policy', null, InputOption::VALUE_NONE, 'Create a new policy for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder for the model'],
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API controller'],
            ['requests', 'R', InputOption::VALUE_NONE, 'Create new form request classes and use them in the resource controller'],
        ];

        foreach ($options as $option) {
            $this->addOption(...$option);
        }
    }

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
        $this->addOptions();
    }

    protected function getNamespace($name)
    {
        return "Modules\\" . $this->argument('module') . "\\Models";
    }
}
