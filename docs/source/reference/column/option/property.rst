property
~~~~~~~~

**type**: `string`

Defines which property of the resource is used for the column. The resource has to provide a getter method for that property.

.. code-block:: yaml

    column:
        myColumn:
            property: myEntityProperty