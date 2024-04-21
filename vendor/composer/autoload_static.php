<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita57633533cc1260572b552bd5b4f4227
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita57633533cc1260572b552bd5b4f4227::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita57633533cc1260572b552bd5b4f4227::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita57633533cc1260572b552bd5b4f4227::$classMap;

        }, null, ClassLoader::class);
    }
}