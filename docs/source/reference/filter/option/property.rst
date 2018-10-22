.. _property:

property
~~~~~~~~

**type**: `string`

Define which property of the resource is used for this filter. The resource has to provide a getter method for that property.

.. code-block:: yaml

    filters:
        myFilter:
            property: name