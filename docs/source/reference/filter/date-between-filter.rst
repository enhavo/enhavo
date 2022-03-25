Date Between Filter
===================

The DateBetweenFilter can be used to filter for dates in a range.

+-------------+--------------------------------------------------------------------+
| type        | text                                                               |
+-------------+--------------------------------------------------------------------+
| required    | - property_                                                        |
+-------------+--------------------------------------------------------------------+
| option      | - label_from_                                                      |
|             | - label_to_                                                        |
|             | - label_                                                           |
|             | - locale_                                                          |
|             | - format_                                                          |
|             | - translation_domain_                                              |
|             | - permission_                                                      |
|             | - hidden_                                                          |
|             | - initial_active_                                                  |
|             | - initial_value_                                                   |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\Bundle\\AppBundle\\Filter\\Filter\\DateBetweenFilter`     |
+-------------+--------------------------------------------------------------------+

Required
--------

.. include:: /reference/filter/option/property.rst

Option
------

label_from
~~~~~~~~~~

**type**: `string|null`

The label for the field where the user can set the lower value of the range. It will be translated over the translation service automatically. (See translation_domain)
If not set, the value of label_ will be used. If that one is also null, no label will be displayed.

.. code-block:: yaml

    columns:
        myFilter:
            label_from: myRange from

label_to
~~~~~~~~

**type**: `string|null`

The label for the field where the user can set the lower value of the range. If not set, the value of label_ will be used.
If that one is also null, no label will be displayed. It will be translated over the translation service automatically. (See translation_domain)

.. code-block:: yaml

    columns:
        myFilter:
            label_to: to

label
~~~~~

**type**: `string|null`

The label of the filter in the filter dropdown. If not set, the value of label_from_ will be used.
It will be translated over the translation service automatically. (See translation_domain)

.. code-block:: yaml

    columns:
        myFilter:
            label: myLabel

locale
~~~~~~

**type**: `string`

The locale used for the Datepicker form fields. Defaults is the default locale of the project.

.. code-block:: yaml

    columns:
        myFilter:
            locale: en

format
~~~~~~

**type**: `string`

The date format used for the Datepicker form fields. Uses the date format of vue3-datepicker. Default is `dd.MM.yyyy`.

.. code-block:: yaml

    columns:
        myFilter:
            format: yyyy-MM-dd

.. include:: /reference/filter/option/translation_domain.rst

.. include:: /reference/filter/option/permission.rst

.. include:: /reference/filter/option/hidden.rst

.. include:: /reference/filter/option/initial_active.rst

.. include:: /reference/filter/option/initial_value.rst
