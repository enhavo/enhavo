Entity Filter
=============

The BooleanFilter filters a property for entities as option

+-------------+--------------------------------------------------------------------+
| type        | entity                                                             |
+-------------+--------------------------------------------------------------------+
| required    | - property_                                                        |
|             | - repository_                                                      |
+-------------+--------------------------------------------------------------------+
| option      | - label_                                                           |
|             | - translationDomain_                                               |
|             | - method_                                                          |
|             | - arguments_                                                       |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\AppBundle\\Filter\\Filter\\EntityFilter`                  |
+-------------+--------------------------------------------------------------------+


Required
--------

.. include:: /reference/filter/option/property.rst

repository
~~~~~~~~~~

**type**: `string`

Define the repository

.. code-block:: yaml

    filter:
        myFilter:
            repository: AppBundle\Repository\MyEntity

Option
------

method
~~~~~~

**type**: `string`

Define the function of the repository, which should be called. Default is `findAll`

.. code-block:: yaml

    filter:
        myFilter:
            method: findAll

arguments
~~~~~~~~~

**type**: `array`

Define the arguments, which should be use to call the method. Default is `null`

.. code-block:: yaml

    filter:
        myFilter:
            method: findBy
            arguments: { public: true }


.. include:: /reference/filter/option/label.rst

.. include:: /reference/filter/option/translationDomain.rst














