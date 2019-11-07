<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcdc5a599d581df931f4dd25c7954f583
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RPS\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RPS\\' => 
        array (
            0 => __DIR__ . '/..' . '/susanbuck/rock-paper-scissors/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcdc5a599d581df931f4dd25c7954f583::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcdc5a599d581df931f4dd25c7954f583::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
