TableBlock
==========

+-------------+--------------------------------------------------------------------+
| type        | table                                                              |
+-------------+--------------------------------------------------------------------+
| require     | - table_route_                                                     |
|             | - update_route_                                                    |
+-------------+--------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Block\\Block\\TableBlock`       |
+-------------+--------------------------------------------------------------------+

Require
-------

.. _table_route:

table_route
~~~~~~~~~~~

**type**: `string`

Define which route should be used for refreshing the table.

.. code-block:: yaml

    blocks:
        table:
            type: table
            route: my_table_route
            # ... further option

.. _update_route:

update_route
~~~~~~~~~~~

**type**: `string`

Define which route should be used for the on row click overlay.

.. code-block:: yaml

    blocks:
        table:
            type: table
            route: my_update_route
            # ... further option



