<?php

namespace Rp76\Module\Command;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use function PHPUnit\Framework\fileExists;

class Controller extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:controller
    {name : The name of the migration}
    {module : The name of the module}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create new controller';

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
        $this->addOptions();
    }

    public function handle()
    {
        $name = $this->argument("name");
        $module = $this->argument("module");

        $this->line("<fg=yellow>It may take a few second...</>");

        if (fileExists(base_path("modules/{$module}/Controllers/{$name}.php")) and !$this->option("force")) {
            $this->line("<fg=red>Controller already exists.</>");
            return;
        }

        $this->files->makeDirectory("modules/{$module}/Controllers", 0777, true, true);
        $this->files->put(base_path("modules/{$module}/Controllers/{$name}.php"), $this->sortImports($this->buildClass($name)));

        $this->line("<fg=green>controller created successfully.</>");
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        $stubPath = str_replace("/ControllerMakeCommand.php", "", (new \ReflectionClass(ControllerMakeCommand::class))->getFileName()) . $stub;

        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : $stubPath;
    }

    protected function getStub()
    {
        $stub = null;

        if ($type = $this->option('type')) {
            $stub = "/stubs/controller.{$type}.stub";
        } elseif ($this->option('invokable')) {
            $stub = '/stubs/controller.invokable.stub';
        } elseif ($this->option('resource')) {
            $stub = '/stubs/controller.stub';
        }

        if ($this->option('api') && is_null($stub)) {
            $stub = '/stubs/controller.api.stub';
        } elseif ($this->option('api') && !is_null($stub) && !$this->option('invokable')) {
            $stub = str_replace('.stub', '.api.stub', $stub);
        }

        $stub = $stub ?? '/stubs/controller.plain.stub';

        return $this->resolveStubPath($stub);
    }

    private function addOptions(): void
    {
        $options = [
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],
            ['type', null, InputOption::VALUE_REQUIRED, 'Manually specify the controller stub file to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
            ['invokable', 'i', InputOption::VALUE_NONE, 'Generate a single method, invokable controller class.'],
//            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.'],
//            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.'],
            ['requests', 'R', InputOption::VALUE_NONE, 'Generate FormRequest classes for store and update.'],
        ];

        foreach ($options as $option) {
            $this->addOption(...$option);
        }
    }

    protected function getNamespace($name)
    {
        return "Modules\\" . $this->argument('module') . "\\Controllers";
    }
}
