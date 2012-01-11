<?php

set_time_limit(0);

$deps = array(
    'symfony'                                               => array('url' => 'http://github.com/symfony/symfony.git'),
    'SensioGeneratorBundle'                                 => array(
        'url'  => 'http://github.com/sensio/SensioGeneratorBundle.git',
        'path' => 'bundles/Sensio/Bundle/SensioGeneratorBundle'
    ),
    'doctrine-common'                                       => array('url' => 'http://github.com/doctrine/common.git'),
    'doctrine'                                              => array('url' => 'http://github.com/doctrine/doctrine2.git'),
    'doctrine-mongodb-odm'                                  => array('url' => 'http://github.com/doctrine/mongodb-odm.git'),
    'doctrine-mongodb'                                      => array('url' => 'http://github.com/doctrine/mongodb.git'),
    'DoctrineMongoDBBundle'                                 => array(
        'url'  => 'https://github.com/doctrine/DoctrineMongoDBBundle.git',
        'path' => 'bundles/Symfony/Bundle/DoctrineMongoDBBundle'
    )
);

foreach ($deps as $name => $dep) {
    $version = isset($dep['version']) ? $dep['version'] : 'origin/master';

    fwrite(STDOUT, '> Installing/Updating '.$name.PHP_EOL);

    $path = isset($dep['path']) ? $dep['path'] : $name;
    $destination = __DIR__.DIRECTORY_SEPARATOR.$path;
    if (!is_dir($destination)) {
        system(sprintf('git clone -q %s %s', escapeshellarg($dep['url']), escapeshellarg($destination)));
    }

    system(sprintf('cd %s && git fetch -q origin && git reset --hard %s', escapeshellarg($destination), escapeshellarg($version)));
}