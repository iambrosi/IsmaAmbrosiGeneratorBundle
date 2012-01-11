Generating a New Doctrine Entity Stub
=====================================

Usage
-----

The ``generate:doctrine:document`` command generates a new Doctrine document stub
including the mapping definition and the class properties, getters and setters.

By default the command is run in the interactive mode and asks questions to
determine the bundle name, location, configuration format and default
structure:

.. code-block:: bash

    php app/console generate:doctrine:document

The command can be run in a non interactive mode by using the
``--non-interaction`` option without forgetting all needed options:

.. code-block:: bash

    php app/console generate:doctrine:document --non-interaction --document=AcmeBlogBundle:Post --fields="title:string(100) body:text" --format=xml

Available Options
-----------------

* ``--document``: The document name given as a shortcut notation containing the
  bundle name in which the document is located and the name of the document. For
  example: ``AcmeBlogBundle:Post``:

    .. code-block:: bash

        php app/console generate:doctrine:document --document=AcmeBlogBundle:Post

* ``--fields``: The list of fields to generate in the document class:

    .. code-block:: bash

        php app/console generate:doctrine:document --fields="title:string(100) body:text"

* ``--format``: (**annotation**) [values: yml, xml, php or annotation] This
  option determines the format to use for the generated configuration files
  like routing. By default, the command uses the ``annotation`` format:

    .. code-block:: bash

        php app/console generate:doctrine:document --format=annotation

* ``--with-repository``: This option tells whether or not to generate the
  related Doctrine `EntityRepository` class:

    .. code-block:: bash

        php app/console generate:doctrine:document --with-repository
