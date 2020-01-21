List Column
===========

Show all values of a collection as a list.

+-------------+--------------------------------------------------------------------+
| type        | list                                                               |
+-------------+--------------------------------------------------------------------+
| require     | - property_                                                        |
|             | - item_property_                                                   |
+-------------+--------------------------------------------------------------------+
| option      | - separator_                                                       |
|             | - width_                                                           |
|             | - label_                                                           |
|             | - translationDomain_                                               |
+-------------+--------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Table\\Column\\ListColumn`      |
+-------------+--------------------------------------------------------------------+


Require
-------

.. include:: /reference/column/option/property.rst

.. _item_property:

item_property
~~~~~~~~~~~~~

**type**: `string`

Define the property of the items which should be used to display inside the collection.

.. code-block:: yaml

    buttons:
        myColumn:
            property: groups
            item_property: name

Option
------

.. include:: /reference/column/option/separator.rst

.. include:: /reference/column/option/width.rst

.. include:: /reference/column/option/label.rst

.. include:: /reference/column/option/translationDomain.rst

