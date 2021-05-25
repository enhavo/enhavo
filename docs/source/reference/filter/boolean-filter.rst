Boolean Filter
==============

The BooleanFilter filters a boolean property. It has two distinct variants depending on the parameter checkbox_.

If checkbox is true, the filter will be rendered as a checkbox. If the checkbox is unchecked, the filter will be
inactive and allow any value on the target's property_ field. If the checkbox is checked, only values equal to this
filter's parameter equals_ are allowed. In the checkbox variant, there is no way to filter for the opposite of what
the parameter equals is set to.

If checkbox is false, the filter will be rendered as a dropdown with both the option of filtering for true, for false
and for an inactive filter.

+-------------+--------------------------------------------------------------------+
| type        | boolean                                                            |
+-------------+--------------------------------------------------------------------+
| required    | - property_                                                        |
|             | - label_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - checkbox_                                                        |
|             | - equals_                                                          |
|             | - label_true_                                                      |
|             | - label_false_                                                     |
|             | - translation_domain_                                              |
|             | - permission_                                                      |
|             | - hidden_                                                          |
|             | - initial_active_                                                  |
|             | - initial_value_                                                   |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\Bundle\\AppBundle\\Filter\\Filter\\BooleanFilter`         |
+-------------+--------------------------------------------------------------------+


Required
--------

.. include:: /reference/filter/option/property.rst

.. include:: /reference/filter/option/label.rst

Option
------

checkbox
~~~~~~~~

**type**: `boolean`

Controls whether the filter is displayed as a checkbox or a dropdown. Default is `true`.

.. code-block:: yaml

    filter:
        myFilter:
            type: boolean
            checkbox: false

equals
~~~~~~

**type**: `boolean`

Only used if checkbox_ is true. Controls whether an active filter allows for the property_ to be true or false. Default is `true`.

.. code-block:: yaml

    filter:
        myFilter:
            type: boolean
            equals: true

label_true
~~~~~~~~~~

**type**: `string`

Only used if checkbox_ is false. Controls the label of the dropdown entry for the value true. Default `filter.boolean.label_true`.
Will be translated over the translation service automatically. (See translation_domain)

.. code-block:: yaml

    filter:
        myFilter:
            type: boolean
            label_true: Yes

label_false
~~~~~~~~~~~

**type**: `string`

Only used if checkbox_ is false. Controls the label of the dropdown entry for the value false. Default `filter.boolean.label_false`.
Will be translated over the translation service automatically. (See translation_domain)

.. code-block:: yaml

    filter:
        myFilter:
            type: boolean
            label_false: No

.. include:: /reference/filter/option/translation_domain.rst

.. include:: /reference/filter/option/permission.rst

.. include:: /reference/filter/option/hidden.rst

.. include:: /reference/filter/option/initial_active.rst

.. include:: /reference/filter/option/initial_value.rst














