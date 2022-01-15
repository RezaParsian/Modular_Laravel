<?php

namespace Rp76\Module\Command;

use Illuminate\Console\Command;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Console\Migrations\TableGuesser;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use function PHPUnit\Framework\fileExists;

class Migration extends Command
{
    /**
     * The migration creator instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationCreator
     */
    protected $creator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migration {name : The name of the migration}
        {module : The name of the module}
        {--create= : The table to be created}
        {--table= : The table to migrate}';

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
        $this->creator=new MigrationCreator(new Filesystem(),public_path("stubs"));
    }

    public function handle()
    {
        $name = $this->argument("name");
        $module = $this->argument("module");

        $this->line("<fg=yellow>It may take a few second...</>");

        $name = Str::snake(trim($this->input->getArgument('name')));

        $table = $this->input->getOption('table');

        $create = $this->input->getOption('create') ?: false;

        if (!$table && is_string($create)) {
            $table = $create;

            $create = true;
        }

        if (!$table) {
            [$table, $create] = TableGuesser::guess($name);
        }

        $file = $this->creator->create(
            $name, "modules/{$module}/Migrations/", $table, $create
        );


        $this->line("<fg=green>migration created successfully.</>");
    }
}
