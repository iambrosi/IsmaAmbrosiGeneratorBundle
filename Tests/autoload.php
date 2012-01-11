<?php

$vendorsDir = realpath(__DIR__.'/../vendor');
require_once $vendorsDir.'/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'           => array($vendorsDir.'/symfony/src', $vendorsDir.'/bundles'),
    'Sensio'            => $vendorsDir.'/bundles',
    'Doctrine\\Common'  => $vendorsDir.'/doctrine-common/lib',
    'Doctrine\\MongoDB' => $vendorsDir.'/doctrine-mongodb/lib',
    'Doctrine'          => $vendorsDir.'/doctrine/lib'
));
$loader->register();

spl_autoload_register(function($class) {
    if (0 === strpos($class, 'IsmaAmbrosi\\Bundle\\GeneratorBundle\\')) {
        $path = __DIR__.'/../'.implode('/', array_slice(explode('\\', $class), 3)).'.php';

        if (!stream_resolve_include_path($path)) {
            return false;
        }
        require_once $path;
        return true;
    }
});