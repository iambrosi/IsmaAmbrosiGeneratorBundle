IsmaAmbrosiGeneratorBundle
==========================

The ``IsmaAmbrosiGeneratorBundle`` extends the ``SensioGeneratorBundle`` adding
the necessary commands to generate documents, forms and crud for MongoDB schemas.

Installation
------------

`Download`_ the bundle and put it under the ``IsmaAmbrosi\\Bundle\\`` namespace.
Then, like for any other bundle, include it in your Kernel class::

    public function registerBundles()
    {
        $bundles = array(
            ...

            new IsmaAmbrosi\Bundle\GeneratorBundle\IsmaAmbrosiGeneratorBundle(),
        );

        ...
    }