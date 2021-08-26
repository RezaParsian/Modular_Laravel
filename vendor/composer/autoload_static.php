<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2941d75f202ee61808356ab422513bcb
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Rp76\\Module\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Rp76\\Module\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2941d75f202ee61808356ab422513bcb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2941d75f202ee61808356ab422513bcb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2941d75f202ee61808356ab422513bcb::$classMap;

        }, null, ClassLoader::class);
    }
}