Installation
============

.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/app-bundle ^0.8

Webpack Config
--------------

Add following ``webpack.config.js`` to your project root directory.

.. code::

    const Encore = require('@symfony/webpack-encore');
    const EnhavoEncore = require('@enhavo/core/EnhavoEncore');

    Encore
      .setOutputPath('public/build/enhavo/')
      .setPublicPath('/build/enhavo')
      .enableSingleRuntimeChunk()
      .enableSourceMaps(!Encore.isProduction())
      .splitEntryChunks()
      .autoProvidejQuery()
      .enableVueLoader()
      .enableSassLoader()
      .enableTypeScriptLoader()
      .enableVersioning(Encore.isProduction())

      .addEntry('enhavo/main', './assets/enhavo/main')
      .addEntry('enhavo/index', './assets/enhavo/index')
      .addEntry('enhavo/view', './assets/enhavo/view')
      .addEntry('enhavo/form', './assets/enhavo/form')
      .addEntry('enhavo/preview', './assets/enhavo/preview')
      .addEntry('enhavo/delete', './assets/enhavo/delete')
      .addEntry('enhavo/list', './assets/enhavo/list')
    ;

    enhavoConfig = EnhavoEncore.getWebpackConfig(Encore.getWebpackConfig());
    enhavoConfig.name = 'enhavo';

    Encore.reset();

    Encore
      .setOutputPath('public/build/theme/')
      .setPublicPath('/build/theme')
      .enableSingleRuntimeChunk()
      .enableSourceMaps(!Encore.isProduction())
      .splitEntryChunks()
      .autoProvidejQuery()
      .enableVueLoader()
      .enableSassLoader()
      .enableTypeScriptLoader()
      .enableVersioning(Encore.isProduction())

      .addEntry('base', './assets/theme/base')

      .copyFiles({
        from: './assets/theme/images',
        to: 'images/[path][name].[ext]'
      })
    ;

    themeConfig = EnhavoEncore.getWebpackConfig(Encore.getWebpackConfig());
    themeConfig.name = 'theme';

    module.exports = [enhavoConfig, themeConfig];


Typescript config
-----------------

Add following ``tsconfig.js`` to your project root directory.

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


Packages
--------

Add following ``package.json`` to your project root directory.

.. code::

    {
      "scripts": {
        "routes:dump": "bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json"
      },
      "devDependencies": {
        "@enhavo/app": "^0.8.0",
        "@symfony/webpack-encore": "0.22.4",
        "copy-webpack-plugin": "^4.5.2",
        "node-sass": "^4.12.0",
        "sass-loader": "^7.1.0",
        "ts-loader": "^5.3",
        "typescript": "^3.1.1",
        "vue": "^2.5.22",
        "vue-loader": "^15.5.1",
        "vue-property-decorator": "^6.0.0",
        "vue-template-compiler": "^2.5.22"
      }
    }

Build
-----

Execute following commands to build needed files.

.. code::

  $ yarn encore dev
  % yarn routes:dump
