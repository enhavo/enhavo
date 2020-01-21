Multiple Property Column
========================

Show a list of properties of the given resource.

+-------------+---------------------------------------------------------------------------+
| type        | multiply_property                                                         |
+-------------+---------------------------------------------------------------------------+
| require     | - properties_                                                             |
+-------------+---------------------------------------------------------------------------+
| option      | - separator_                                                              |
|             | - width_                                                                  |
|             | - label_                                                                  |
|             | - translationDomain_                                                      |
+-------------+---------------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Table\\Column\\MultiplePropertyColumn` |
+-------------+---------------------------------------------------------------------------+


Require
-------

.. _properties:

properties
~~~~~~~~~~

**type**: `string[]`

Define a array of properties that should be displayed as list.

.. code-block:: yaml

    buttons:
        myColumn:
            properties:
                - firstname
                - lastname

Option
------

.. include:: /reference/column/option/separator.rst

.. include:: /reference/column/option/width.rst

.. include:: /reference/column/option/label.rst

.. include:: /reference/column/option/translationDomain.rst
