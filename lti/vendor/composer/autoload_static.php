<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite16a9715371157d3aed769f7251f287a
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'IMSGlobal\\LTI\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'IMSGlobal\\LTI\\' => 
        array (
            0 => __DIR__ . '/..' . '/imsglobal/lti/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite16a9715371157d3aed769f7251f287a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite16a9715371157d3aed769f7251f287a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
