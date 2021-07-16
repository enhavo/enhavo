Option Filter
=============

The OptionFilter filters a property for specific options.

+-------------+--------------------------------------------------------------------+
| type        | option                                                             |
+-------------+--------------------------------------------------------------------+
| required    | - options_                                                         |
|             | - property_                                                        |
|             | - label_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - translation_domain_                                              |
|             | - permission_                                                      |
|             | - hidden_                                                          |
|             | - initial_active_                                                  |
|             | - initial_value_                                                   |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\Bundle\\AppBundle\\Filter\\Filter\\OptionFilter`          |
+-------------+--------------------------------------------------------------------+


Required
--------

options
~~~~~~~

**type**: `array`

Define the options, which the user can choose

.. code-block:: yaml

    filter:
        myFilter:
            options:
                Foo: Bar
                Hello: World

.. include:: /reference/filter/option/property.rst

.. include:: /reference/filter/option/label.rst

Option
------

.. include:: /reference/filter/option/translation_domain.rst

.. include:: /reference/filter/option/permission.rst

.. include:: /reference/filter/option/hidden.rst

.. include:: /reference/filter/option/initial_active.rst

initial_value
~~~~~~~~~~~~~

**type**: `string|null`

If set, this filter will be initially have a set value and the list will initially be filtered by this value.
This must be NULL or one of the array keys in the parameter options_. Default is `null`.

.. code-block:: yaml

    columns:
        myFilter:
            initial_value: Foo













