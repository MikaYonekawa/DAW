<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita6d95827a05fa42d00badb27f8f8e170
{
    public static $prefixesPsr0 = array (
        'O' => 
        array (
            'OpenBoleto\\' => 
            array (
                0 => __DIR__ . '/..' . '/kriansa/openboleto/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInita6d95827a05fa42d00badb27f8f8e170::$prefixesPsr0;
            $loader->classMap = ComposerStaticInita6d95827a05fa42d00badb27f8f8e170::$classMap;

        }, null, ClassLoader::class);
    }
}
