Auto Complete Filter
====================

The EntityFilter filters a property for entities as a dropdown.

It is not recommended to use this filter if the number of possible entities is possible to become very big.
If this is the case, consider using AutoCompleteEntityFilter instead.

+-------------+--------------------------------------------------------------------+
| type        | entity                                                             |
+-------------+--------------------------------------------------------------------+
| required    | - route_                                                           |
|             | - property_                                                        |
|             | - label_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - route_parameters_                                                |
|             | - minimum_input_length_                                            |
|             | - translation_domain_                                              |
|             | - permission_                                                      |
|             | - hidden_                                                          |
|             | - initial_active_                                                  |
|             | - initial_value_                                                   |
|             | - initial_value_arguments_                                         |
|             | - initial_value_repository_                                        |
|             | - initial_value_choice_label_                                      |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\Bundle\\AppBundle\\Filter\\Filter\\EntityFilter`          |
+-------------+--------------------------------------------------------------------+


Required
--------

route
~~~~~

**type**: `string`

Route that will be called for the autocomplete search. The route will be used in the following way:

- Get-Parameter `q` will have the search term as a string
- Get-Parameter `page` will be an integer representing the pagination page
- Additional parameters may be defined via the parameter route_parameters_
- The result must be a json array with the results in the format: ``[{ code: id; label: "Label" }]``

.. code-block:: yaml

    filter:
        myFilter:
            route: my_entity_autocomplete_route

.. include:: /reference/filter/option/property.rst

.. include:: /reference/filter/option/label.rst

Option
------

route_parameters
~~~~~~~~~~~~~~~~

**type**: `array`

Optional additional parameters for the route defined in route_. Default is an empty array.

.. code-block:: yaml

    filter:
        myFilter:
            route: my_entity_autocomplete_route
            route_parameters: { foo: "bar" }

minimum_input_length
~~~~~~~~~~~~~~~~~~~~

**type**: `int`

The minimum number of characters before an autocomplete search is started. Default is `3`.

.. code-block:: yaml

    filter:
        myFilter:
            minimum_input_length: 5

.. include:: /reference/filter/option/translation_domain.rst

.. include:: /reference/filter/option/permission.rst

.. include:: /reference/filter/option/hidden.rst

.. include:: /reference/filter/option/initial_active.rst

initial_value
~~~~~~~~~~~~~

**type**: `string|null`

If set, this filter will be initially have a set value and the list will initially be filtered by this value.
This must be a method of the repository defined by the parameter initial_value_repository_ which returns either a single
object or an array with at least one entry (the first entry will be used).
Default `null`.

.. code-block:: yaml

    columns:
        myFilter:
            initial_value: findByFoo
            initial_value_repository: AppBundle\Repository\MyEntityRepository

initial_value_arguments
~~~~~~~~~~~~~~~~~~~~~~~

**type**: `array|null`

Optional arguments that will be added to the call of the repository method in parameter initial_value_. Default is `null`.

.. code-block:: yaml

    columns:
        myFilter:
            initial_value: findByFoo
            initial_value_arguments: { foo: 'bar' }

initial_value_repository
~~~~~~~~~~~~~~~~~~~~~~~~

**type**: `string|null`

This parameter is needed and no longer optional if initial_value_ is not null.

Either the name of a public service that points to the entity's repository or the FQCN of the entity to be used on
EntityManager::getRepository(). This will be used to find the initial value.

Default is `null`.

.. code-block:: yaml

    columns:
        myFilter:
            initial_value: findByFoo
            initial_value_repository: AppBundle\Repository\MyEntityRepository

initial_value_choice_label
~~~~~~~~~~~~~~~~~~~~~~~~~~

**type**: `string|null`

Property of the entity that will be used as label of the initial value if initial_value_ is set. Default is `null`.

.. code-block:: yaml

    filter:
        myFilter:
            initial_value: findByFoo
            initial_value_repository: AppBundle\Repository\MyEntityRepository
            initial_value_choice_label: title
