Add JS file
===========


Add file
--------

First add the assets path to your configuration. This is needed to know where we can load your js files.

.. code-block:: yaml

    enhavo_assets:
        require_js:
            paths:
                project:
                    location: /bundles/project/js


Then you can create your js under the bundle path. Because we using RequireJS to load the files, just add the define
syntax to load other dependencies.

.. code-block:: javascript

    define(['jquery', 'app/Router'], function($, router) {
      return new (function() {
        // ... your functions
      });
    });

Include file
------------

**Block**

To add your file in a block just add it to your route definition.

.. code-block:: yaml

    route_name:
        defaults:
            _sylius:
                viewer:
                    blocks:
                        myBlock:
                            app: project/myJsFile


