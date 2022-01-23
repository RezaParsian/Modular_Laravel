<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Rp76\Module\Helper\Command as RpCommand;
use Symfony\Component\Console\Input\InputOption;
use function PHPUnit\Framework\fileExists;
use function Symfony\Component\Translation\t;

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

        if (file_exists(base_path("modules/{$module}/Models/{$name}.php")) and !$this->option("force")) {
            $this->line("<fg=red>Model already exists.</>");
            return;
        }

        if ($this->option("controller"))
            $this->makeController($module);


        if ($this->option("migration"))
            $this->makeMigration($name, $module);

        $this->line("<fg=yellow>It may take a few second...</>");

        $this->files->makeDirectory("modules/{$module}/Models", 0777, true, true);
        $this->files->put(base_path("modules/{$module}/Models/{$name}.php"), $this->sortImports($this->buildClass($name)));

        $this->line("<fg=green>model created successfully.</>");
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/model.stub');
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
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API controller'],
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

    /**
     * @param $module
     */
    protected function makeController($module): void
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $this->call('module:controller', array_filter([
            'name' => "{$controller}Controller",
            'module' => $module,
            "--resource" => $this->option("resource"),
            "--force" => $this->option("force"),
            '--api' => $this->option('api'),
        ]));
    }

    /**
     * @param $name
     * @param $module
     */
    protected function makeMigration($name, $module): void
    {
        $table = Str::snake(Str::pluralStudly(class_basename($name)));

        $this->call('module:migration', [
            'name' => "create_{$table}_table",
            "module" => $module,
            "--force" => $this->option("force"),
            '--create' => $table,
        ]);
    }
}
