sorting_property
~~~~~~~~~~~~~~~~

**type**: `string`
**default**: |default_sorting_property|

Defines which property of the resource the column is sorted by. The resource must provide a getter method for this property.

.. code-block:: yaml

    columns:
        myColumn:
            sorting_property: myEntityProperty
            # ... further option