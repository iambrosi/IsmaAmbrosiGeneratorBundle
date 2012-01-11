Generating a New Form Type Class Based on a Doctrine Entity
===========================================================

Usage
-----

The ``generate:doctrine:form`` generates a basic form type class by using the
metadata mapping of a given document class:

.. code-block:: bash

    php app/console generate:doctrine:form AcmeBlogBundle:Post

Required Arguments
------------------

* ``document``: The document name given as a shortcut notation containing the
  bundle name in which the document is located and the name of the document. For
  example: ``AcmeBlogBundle:Post``:

    .. code-block:: bash

        php app/console generate:doctrine:form AcmeBlogBundle:Post
