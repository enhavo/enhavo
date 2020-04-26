Installation
============


.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/form-bundle ^0.8


.. include:: /book/_includes/installation/node-package.rst

.. code::

  $ yarn add @enhavo/form --dev

.. include:: /book/_includes/installation/register-form-package.rst

.. code:: ts

    // import statement
    import FormFormRegistryPackage from "@enhavo/form/FormRegistryPackage";

    // register the package
    this.registerPackage(new FormFormRegistryPackage(application));
