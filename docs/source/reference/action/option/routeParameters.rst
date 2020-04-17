route_parameters
~~~~~~~~~~~~~~~~

**type**: `array`
**default**: |default_route_parameters|

If route is defined, you can overwrite the standard parameters to generate your own url.

.. code-block:: yaml

    actions:
        myAction:
            route_parameters:
                id: $id