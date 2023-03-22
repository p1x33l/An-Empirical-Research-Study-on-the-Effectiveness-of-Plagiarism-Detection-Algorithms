<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2d5f01aad3f93f4cddd4a4d39df23ab1
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Eloquent\\Lcs\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Eloquent\\Lcs\\' => 
        array (
            0 => __DIR__ . '/..' . '/eloquent/lcs/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2d5f01aad3f93f4cddd4a4d39df23ab1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2d5f01aad3f93f4cddd4a4d39df23ab1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2d5f01aad3f93f4cddd4a4d39df23ab1::$classMap;

        }, null, ClassLoader::class);
    }
}
