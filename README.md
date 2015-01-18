# IsmaAmbrosiGeneratorBundle 

[![Build Status](https://secure.travis-ci.org/iambrosi/IsmaAmbrosiGeneratorBundle.png?branch=master)](http://travis-ci.org/iambrosi/IsmaAmbrosiGeneratorBundle)
[![Total Downloads](https://poser.pugx.org/ismaambrosi/generator-bundle/downloads.png)](https://packagist.org/packages/ismaambrosi/generator-bundle)
[![Latest Stable Version](https://poser.pugx.org/ismaambrosi/generator-bundle/v/stable.png)](https://packagist.org/packages/ismaambrosi/generator-bundle)
[![Latest Unstable Version](https://poser.pugx.org/ismaambrosi/generator-bundle/v/unstable.png)](https://packagist.org/packages/ismaambrosi/generator-bundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/54e00601-0232-471a-ae8e-b5d534a3a819/mini.png)](https://insight.sensiolabs.com/projects/54e00601-0232-471a-ae8e-b5d534a3a819)

This bundle extends the commands provided by [SensioGeneratorBundle](https://github.com/sensio/SensioGeneratorBundle), adding a MongoDB document generator and CRUD generators for those MongoDB documents.


Installation
------------

### Add the bundle to your project.

Add the requirement to composer:

```bash
$ php composer.phar require ismaambrosi/generator-bundle
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


### Commands

This bundle contains three commands that will allow you to generate code for documents, forms and CRUD controllers. These commands can be executed either on interactive mode or manual mode. I would recommend you to use the interactive mode.

#### Generating ODM documents

The first command allows to generate the document classes.

Examples:

```bash
$ php app/console doctrine:mongodb:generate:document
```

```bash
$ php app/console doctrine:mongodb:generate:document \
--document=AcmeBlogBundle:Blog/Post \
--with-repository
```


#### Generating forms

With the second command we can generate the form type classes, used by the form component.

Example:

```bash
$ php app/console doctrine:mongodb:generate:form AcmeBlogBundle:Post
```

#### Generating the CRUD

The last command generates the CRUD controllers, with read-only actions to handle the documents that were generated previously. It also allows to include the _write actions_, for creating, updating and deleting documents.

Examples:

```bash
$ php app/console doctrine:mongodb:generate:crud
```

```bash
# Specifying the document and the routing prefix
$ php app/console doctrine:mongodb:generate:crud \
--document=AcmeBlogBundle:Post \
--route-prefix=post_admin
```

```bash
# Specifying the document, routing and write-actions
$ php app/console doctrine:mongodb:generate:crud \
--document=AcmeBlogBundle:Post \
--route-prefix=post_admin --with-write
```
