Installation
============

.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/media-bundle ^0.8


.. include:: /book/_includes/installation/node-package.rst

.. code::

  $ yarn add @enhavo/media


.. include:: /book/_includes/installation/register-form-package.rst

.. code:: ts

    // import statement
    import MediaFormRegistryPackage from "@enhavo/media/FormRegistryPackage";

    // register the package
    this.registerPackage(new MediaFormRegistryPackage(application));


.. include:: /book/_includes/installation/register-encore-package.rst

.. code::

  // import
  const MediaPackage = require('@enhavo/media/Encore/EncoreRegistryPackage');

  // register package
  .register(new MediaPackage());


.. include:: /book/_includes/installation/change-configuration.rst

If you want to display the media library in your application you can change your ``config/packages/enhavo.yaml`` file.

.. code:: yaml

    enhavo_app:
        menu:
            media_library:
                type: media_library

.. include:: /book/_includes/installation/migrate-database.rst

.. include:: /book/_includes/installation/build-assets.rst
