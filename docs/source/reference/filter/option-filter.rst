OptionFilter
============

The OptionFilter filters a property for specific options

+-------------+--------------------------------------------------------------------+
| type        | option                                                             |
+-------------+--------------------------------------------------------------------+
| required    | - property_                                                        |
|             | - options_                                                         |
+-------------+--------------------------------------------------------------------+
| option      | - label_                                                           |
|             | - translationDomain_                                               |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\AppBundle\\Filter\\Filter\\OptionFilter`                  |
+-------------+--------------------------------------------------------------------+


Required
--------

.. include:: /reference/filter/option/property.rst

options
~~~~~~~

**type**: `array`

Define the options, which the use can choose

.. code-block:: yaml

    filter:
        myFilter:
            options:
                Foo: Bar
                Hello: World

Option
------

.. include:: /reference/filter/option/label.rst

.. include:: /reference/filter/option/translationDomain.rst













