Taxonomy Filter
===============

The TaxonomyFilter filters by a taxonomy.

+-------------+--------------------------------------------------------------------+
| type        | taxonomy                                                           |
+-------------+--------------------------------------------------------------------+
| required    | - taxonomy_                                                        |
|             | - property_                                                        |
|             | - label_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - translation_domain_                                              |
|             | - permission_                                                      |
|             | - hidden_                                                          |
|             | - initial_active_                                                  |
|             | - initial_value_                                                   |
|             | - initial_value_method_                                            |
|             | - initial_value_arguments_                                         |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\Bundle\\TaxonomyBundle\\Filter\\TaxonomyFilterType`       |
+-------------+--------------------------------------------------------------------+

Required
--------

taxonomy
~~~~~~~~

**type**: `string`

The slug of the Taxonomy.

.. code-block:: yaml

    columns:
        myFilter:
            type: taxonomy
            operator: article_category

.. include:: /reference/filter/option/property.rst

.. include:: /reference/filter/option/label.rst

Option
------

.. include:: /reference/filter/option/translation_domain.rst

.. include:: /reference/filter/option/permission.rst

.. include:: /reference/filter/option/hidden.rst

.. include:: /reference/filter/option/initial_active.rst

.. include:: /reference/filter/option/initial_value.rst

initial_value_method
~~~~~~~~~~~~~~~~~~~~

**type**: `string|null`

Defines the repository method to use when setting the initial value. Default `findOneByNameAndTaxonomy`.
If multiple results are returned by the method, the first one is used.

.. code-block:: yaml

    columns:
        myFilter:
            initial_value: Foo
            initial_value_method: findOneByBar

initial_value_arguments
~~~~~~~~~~~~~~~~~~~~~~~

**type**: `string|null`

Optional arguments that will be added to the call of the repository method.
Default is an array containing the values of parameters initial_value_ and taxonomy_.

.. code-block:: yaml

    columns:
        myFilter:
            initial_value: Foo
            initial_value_method: findOneByFoo
            initial_value_arguments: { foo: 'bar' }
