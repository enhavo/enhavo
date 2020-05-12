Installation
============

.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/dashboard-bundle ^0.8


.. include:: /book/_includes/installation/node-package.rst

.. code::

  $ yarn add @enhavo/dashboard


.. include:: /book/_includes/installation/register-encore-package.rst

.. code::

  // import
  const DashboardPackage = require('@enhavo/dashboard/Encore/EncoreRegistryPackage');

  // register package
  .register(new DashboardPackage())

.. include:: /book/_includes/installation/change-configuration.rst

Update your ``config/packages/enhavo.yaml``

.. code:: yaml

    enhavo_app:
        menu:
          dashboard:
              type: dashboard

.. include:: /book/_includes/installation/build-assets.rst
