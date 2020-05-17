Installation
============


.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/form-bundle ^0.8


.. include:: /book/_includes/installation/node-package.rst

.. code::

  $ yarn add @enhavo/form

.. include:: /book/_includes/installation/register-form-package.rst

.. code:: ts

    // import statement
    import FormFormRegistryPackage from "@enhavo/form/FormRegistryPackage";

    // register the package
    this.registerPackage(new FormFormRegistryPackage(application));


.. include:: /book/_includes/installation/register-encore-package.rst

.. code::

  // import
  const FormPackage = require('@enhavo/form/Encore/EncoreRegistryPackage');

  // register package
  .register(new FormPackage())

.. include:: /book/_includes/installation/build-assets.rst
