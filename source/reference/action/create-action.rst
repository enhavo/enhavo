CreateAction
============

The CreateAction represents a create button for a specific route

+-------------+--------------------------------------------------------------------+
| type        | create                                                             |
+-------------+--------------------------------------------------------------------+
| require     | - route_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - label_                                                           |
|             | - icon_                                                            |
|             | - translationDomain_                                               |
+-------------+--------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Action\\Action\\CreateAction`   |
+-------------+--------------------------------------------------------------------+



Require
-------

.. _route:

route
~~~~~

**type**: `string`

Define which route should be used for the create overlay.

.. code-block:: yaml

    actions:
        create:
            type: create
            route: my_create_route


Option
------

.. include:: /reference/action/option/label.rst

.. include:: /reference/action/option/icon.rst

.. include:: /reference/action/option/translationDomain.rst














