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

List of Available Commands
--------------------------

The ``SensioGeneratorBundle`` come with four new commands that can be run in
interactive mode or not. The interactive mode asks you some questions to
configure the command parameters to generate the definitive code. The list of
new commands are listed below:

.. toctree::
   :maxdepth: 1

   commands/generate_bundle
   commands/generate_doctrine_crud
   commands/generate_doctrine_document
   commands/generate_doctrine_form

.. _Download: http://github.com/sensio/SensioGeneratorBundle