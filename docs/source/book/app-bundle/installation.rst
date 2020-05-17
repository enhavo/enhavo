Installation
============

.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/app-bundle ^0.8

Add packages
------------

Add following ``package.json`` to your project root directory.

.. code::

    {
      "scripts": {
        "routes:dump": "bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json"
      },
      "dependencies": {
        "@enhavo/app": "^0.8.0",
      }
    }

Add webpack config
------------------

Add following ``webpack.config.js`` to your project root directory.

.. code::

    const EnhavoEncore = require('@enhavo/core/EnhavoEncore');
    const AppPackage = require('@enhavo/app/Encore/EncoreRegistryPackage');


    EnhavoEncore
      // register packages
      .register(new AppPackage())
    ;

    EnhavoEncore.add('enhavo', (Encore) => {
      // custom encore config
      // Encore.enableBuildNotifications();
    });

    EnhavoEncore.add('theme', (Encore) => {
      Encore
        // add theme entry and config
        .addEntry('base', './assets/theme/base')
    });

    module.exports = EnhavoEncore.export();

.. include:: /book/_includes/installation/change-configuration.rst

Update your ``config/packages/webpack_encore.yaml``

.. code:: yaml

  webpack_encore:
      output_path: '%kernel.project_dir%/public/build/enhavo'
      builds:
          enhavo: '%kernel.project_dir%/public/build/enhavo'
          theme: '%kernel.project_dir%/public/build/theme'

Update your ``config/packages/assets.yaml``

.. code:: yaml

  framework:
      assets:
          json_manifest_path: '%kernel.project_dir%/public/build/theme/manifest.json'

This is optional, but it helps to test your mails using within other packages.
Just update your ``config/packages/dev/swiftmailer.yaml``

.. code:: yaml

  swiftmailer:
      delivery_addresses: ['%env(resolve:MAILER_DELIVERY_ADDRESS)%']


Add typescript config
---------------------

Add following ``tsconfig.json`` to your project root directory.

.. code::

    {
      "compilerOptions": {
        "lib": [ "es2015", "dom" ],
        "module": "amd",
        "target": "es5",
        "allowJs": true,
        "noImplicitAny": true,
        "suppressImplicitAnyIndexErrors": true,
        "moduleResolution": "node",
        "sourceMap": true,
        "experimentalDecorators": true
      },
      "include": [
        "./assets/**/*"
      ]
    }

.. include:: /book/_includes/installation/build-assets.rst

Start application
-----------------

Open your application under the ``/admin`` url.
