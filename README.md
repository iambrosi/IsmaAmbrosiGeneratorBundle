IsmaAmbrosiGeneratorBundle
==========================

[![Build Status](https://secure.travis-ci.org/iambrosi/IsmaAmbrosiGeneratorBundle.png?branch=2.0)](http://travis-ci.org/iambrosi/IsmaAmbrosiGeneratorBundle)

This bundle extends the commands provided by [SensioGeneratorBundle](https://github.com/sensio/SensioGeneratorBundle), adding a MongoDB document generator and CRUD generators for these MongoDB documents.

Installation
------------

### Add the bundle to your project.

**Using Symfony's vendors script**

Add the following lines to your deps file:

```
[IsmaAmbrosiGeneratorBundle]
    git=git://github.com/iambrosi/IsmaAmbrosiGeneratorBundle.git
    target=bundles/IsmaAmbrosi/Bundle/GeneratorBundle
    version=origin/2.0
```

And then run the vendors script:

```bash
$ php ./bin/vendors install
```

You will also need to install the DoctrineMongoDBBundle. The instructions on how to install it are available in the Symfony2 [documentation](http://symfony.com/doc/current/bundles/DoctrineMongoDBBundle/index.html).

###Add the namespace to the autoloader

```php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'IsmaAmbrosi' => __DIR__.'/../vendor/bundles',
    // ...
));
```

###Add the bundle to the app Kernel

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
It is recommended to disable this bundle on the production environment.
