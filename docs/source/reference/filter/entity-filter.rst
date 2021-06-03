Entity Filter
=============

The EntityFilter filters a property for entities as a dropdown.

If the number of possible entities is large, this filter will negatively effect performance.
If this is the case, consider using AutoCompleteEntityFilter instead.

+-------------+--------------------------------------------------------------------+
| type        | entity                                                             |
+-------------+--------------------------------------------------------------------+
| required    | - repository_                                                      |
|             | - property_                                                        |
|             | - label_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - method_                                                          |
|             | - arguments_                                                       |
|             | - choice_label_                                                    |
|             | - translation_domain_                                              |
|             | - permission_                                                      |
|             | - hidden_                                                          |
|             | - initial_active_                                                  |
|             | - initial_value_                                                   |
|             | - initial_value_arguments_                                         |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\Bundle\\AppBundle\\Filter\\Filter\\EntityFilter`          |
+-------------+--------------------------------------------------------------------+


Required
--------

repository
~~~~~~~~~~

**type**: `string`

Either the name of a public service that points to the entity's repository or the FQCN of the entity to be used on
EntityManager::getRepository().

.. code-block:: yaml

    filter:
        myFilter:
            repository: AppBundle\Repository\MyEntityRepository

.. include:: /reference/filter/option/property.rst

.. include:: /reference/filter/option/label.rst

Option
------

method
~~~~~~

**type**: `string`

The name of the method of the repository which should be called. Default is `findAll`.

.. code-block:: yaml

    filter:
        myFilter:
            method: findAll

arguments
~~~~~~~~~

**type**: `array|null`

Optional arguments that will be added to the call of the repository method. Default is `null`.

.. code-block:: yaml

    filter:
        myFilter:
            method: findBy
            arguments: { public: true }

choice_label
~~~~~~~~~~~~

**type**: `string|null`

Property of the entity that will be used as label in the options list.

.. code-block:: yaml

    filter:
        myFilter:
            choice_label: title


.. include:: /reference/filter/option/translation_domain.rst

.. include:: /reference/filter/option/permission.rst

.. include:: /reference/filter/option/hidden.rst

.. include:: /reference/filter/option/initial_active.rst

initial_value
~~~~~~~~~~~~~

**type**: `string|null`

If set, this filter will be initially have a set value and the list will initially be filtered by this value.
This must be a method of the repository defined by the parameter repository_ which returns either a single object
or an array with at least one entry (the first entry will be used).
Default `null`.

.. code-block:: yaml

    columns:
        myFilter:
            initial_value: findByFoo

initial_value_arguments
~~~~~~~~~~~~~~~~~~~~~~~

**type**: `array|null`

Optional arguments that will be added to the call of the repository method in parameter initial_value_. Default is `null`.

.. code-block:: yaml

    columns:
        myFilter:
            initial_value: findByFoo
            initial_value_arguments: { foo: 'bar' }
