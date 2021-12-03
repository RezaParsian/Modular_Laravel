<?php

namespace Rp76\Module\Helper;

use Illuminate\Support\Facades\Config;

class Command
{
    /**
     * @param $path
     */
    public static function makePath($path): void
    {
        if (!realpath(base_path($path)))
            mkdir(base_path($path));
    }

    public static function makeFile($path, $moduleName, $tmpName)
    {
        file_put_contents(base_path($path), str_replace("%Rp76%", $moduleName, file_get_contents(__DIR__ . "/../tmp/$tmpName")));
    }

    public static function exec($moduleName, $command)
    {
        shell_exec("cd " . base_path("modules/{$moduleName}") . " && {$command}");
    }

    public static function add_to_config($value)
    {
        $conf = config("RpModule");
        $conf[] = $value;

        file_put_contents(config_path() . "/RpModule.php", "<?php \r return " . json_encode($conf) . ";");

        return $conf;
    }
}
