MultiplePropertyWidget
======================

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
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Table\\Widget\\MultiplePropertyWidget` |
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
        myWidget:
            properties:
                - firstname
                - lastname

Option
------

.. include:: /reference/tableWidget/option/separator.rst

.. include:: /reference/tableWidget/option/width.rst

.. include:: /reference/tableWidget/option/label.rst

.. include:: /reference/tableWidget/option/translationDomain.rst
