# IsmaAmbrosiGeneratorBundle [![Build Status](https://secure.travis-ci.org/iambrosi/IsmaAmbrosiGeneratorBundle.png?branch=master)](http://travis-ci.org/iambrosi/IsmaAmbrosiGeneratorBundle)

[![Latest Stable Version](https://poser.pugx.org/ismaambrosi/generator-bundle/v/stable.png)](https://packagist.org/packages/ismaambrosi/generator-bundle)
[![Total Downloads](https://poser.pugx.org/ismaambrosi/generator-bundle/downloads.png)](https://packagist.org/packages/ismaambrosi/generator-bundle)

This bundle extends the commands provided by [SensioGeneratorBundle](https://github.com/sensio/SensioGeneratorBundle), adding a MongoDB document generator and CRUD generators for those MongoDB documents.

Installation
------------

### Add the bundle to your project.

Add the requirement to composer:

```bash
$ php composer.phar require ismaambrosi/generator-bundle:dev-master
```

You will also need to install the DoctrineMongoDBBundle. The instructions on how to install it are available in the Symfony2 [documentation](http://symfony.com/doc/master/bundles/DoctrineMongoDBBundle/index.html).

### Enable the bundle in your kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    // ...
    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        // ...
        $bundles[] = new IsmaAmbrosi\Bundle\GeneratorBundle\IsmaAmbrosiGeneratorBundle();
    }
}
```
It is recommended to disable this bundle for the production environment.
