Boolean Filter
==============

The BooleanFilter filters a property for a specific value

+-------------+--------------------------------------------------------------------+
| type        | boolean                                                            |
+-------------+--------------------------------------------------------------------+
| required    | - property_                                                        |
|             | - equals_                                                          |
+-------------+--------------------------------------------------------------------+
| option      | - label_                                                           |
|             | - translationDomain_                                               |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\AppBundle\\Filter\\Filter\\BooleanFilter`                 |
+-------------+--------------------------------------------------------------------+


Required
--------

.. include:: /reference/filter/option/property.rst

equals
~~~~~~

**type**: `string|boolean|null`

Define the value, which should be filtered.

.. code-block:: yaml

    filter:
        myFilter:
            type: boolean
            equals: true

Option
------

.. include:: /reference/filter/option/label.rst

.. include:: /reference/filter/option/translationDomain.rst














