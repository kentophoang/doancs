<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit992e7f8d8b51d0b8d78b9a2e52dacb8c
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit992e7f8d8b51d0b8d78b9a2e52dacb8c', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit992e7f8d8b51d0b8d78b9a2e52dacb8c', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit992e7f8d8b51d0b8d78b9a2e52dacb8c::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
