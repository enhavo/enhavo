Text Filter
===========

The TextFilter filters a property by user string

+-------------+--------------------------------------------------------------------+
| type        | text                                                               |
+-------------+--------------------------------------------------------------------+
| required    | - property_                                                        |
|             | - label_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - operator_                                                        |
|             | - translation_domain_                                              |
|             | - permission_                                                      |
|             | - hidden_                                                          |
|             | - initial_active_                                                  |
|             | - initial_value_                                                   |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\Bundle\\AppBundle\\Filter\\Filter\\TextFilter`            |
+-------------+--------------------------------------------------------------------+

Required
--------

.. include:: /reference/filter/option/property.rst

.. include:: /reference/filter/option/label.rst

Option
------

operator
~~~~~~~~

**type**: `string`

The operator used when applying this filter to the database search. Can be one of the following:

- "="
- "!="
- "like" (default)
- "start_like"
- "end_like"

Default is `like`.

.. code-block:: yaml

    columns:
        myFilter:
            type: text
            operator: start_like

.. include:: /reference/filter/option/translation_domain.rst

.. include:: /reference/filter/option/permission.rst

.. include:: /reference/filter/option/hidden.rst

.. include:: /reference/filter/option/initial_active.rst

.. include:: /reference/filter/option/initial_value.rst
