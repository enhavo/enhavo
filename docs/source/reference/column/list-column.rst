List Column
===========

Shows all values of a collection as a list.

.. csv-table::
    :widths: 50 150

    Type , list
    Require ,"- | :ref:`property <property_list>`
    - | :ref:`item_property <item_property_list>`"
    Options ,"- | :ref:`separator <separator_list>`
    - | :ref:`label <label_list>`
    - | :ref:`translation_domain <translation_domain_list>`
    - | :ref:`width <width_list>`
    - | :ref:`sortable <sortable_list>`
    - | :ref:`condition <condition_list>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Column\\Type\\ListType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Column\\AbstractColumnType`

Require
-------

.. _property_list:
.. include:: /reference/column/option/property.rst


.. _item_property_list:

item_property
~~~~~~~~~~~~~

**type**: `string`

Defines the property of the items to be used for display within the collection. You can use existing
properties of the class or a specially created getter method that returns a string composed of multiple properties.

.. code-block:: yaml

    buttons:
        myColumn:
            property: groups
            item_property: myEntityProperty

Options
-------

.. _separator_list:
.. |default_separator| replace:: ','
.. include:: /reference/column/option/separator.rst

.. _label_list:
.. |default_label| replace:: `""`
.. include:: /reference/column/option/label.rst

.. _translation_domain_list:
.. |default_translation_domain| replace:: `null`
.. include:: /reference/column/option/translationDomain.rst

.. _width_list:
.. |default_width| replace:: ``1``
.. include:: /reference/column/option/width.rst

.. _sortable_list:
.. |default_sortable| replace:: false
.. include:: /reference/column/option/sortable.rst

.. _condition_list:
.. |default_condition| replace:: null
.. include:: /reference/column/option/condition.rst