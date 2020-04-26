Installation
============

.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/media-bundle ^0.8


.. include:: /book/_includes/installation/node-package.rst

.. code::

  $ yarn add @enhavo/media --dev


.. include:: /book/_includes/installation/register-form-package.rst

.. code:: ts

    // import statement
    import MediaFormRegistryPackage from "@enhavo/media/FormRegistryPackage";

    // register the package
    this.registerPackage(new MediaFormRegistryPackage(application));
