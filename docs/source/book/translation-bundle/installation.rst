Installation
============

.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/translation-bundle ^0.8

.. include:: /book/_includes/installation/dependencies.rst

* :doc:`RoutingBundle </book/routing-bundle/installation>`
* :doc:`FormBundle </book/form-bundle/installation>`


.. include:: /book/_includes/installation/node-package.rst

.. code::

  $ yarn add @enhavo/translation


.. include:: /book/_includes/installation/register-form-package.rst

.. code:: ts

    // import statement
    import TranslationFormRegistryPackage from "@enhavo/translation/Form/FormRegistryPackage";

    // register the package
    this.registerPackage(new TranslationFormRegistryPackage(application));
