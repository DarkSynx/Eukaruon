<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0dbc9c64796aac9f8428bc9c53813fdd
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Darksynx\\Eukaruon\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Darksynx\\Eukaruon\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit0dbc9c64796aac9f8428bc9c53813fdd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0dbc9c64796aac9f8428bc9c53813fdd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0dbc9c64796aac9f8428bc9c53813fdd::$classMap;

        }, null, ClassLoader::class);
    }
}
