.. _date_column:

Date Column
===========

Shows a formatted date.

.. csv-table::
    :widths: 50 150

    Type , date
    Require ,"- | :ref:`property <property_date>`"
    Options ,"- | :ref:`label <label_date>`
    - | :ref:`format <format_date>`
    - | :ref:`translation_domain <translation_domain_date>`
    - | :ref:`width <width_date>`
    - | :ref:`sortable <sortable_date>`
    - | :ref:`condition <condition_date>`
    - | :ref:`sorting_property <sorting_property_date>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Column\\Type\\DateType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Column\\AbstractColumnType`

Require
-------

.. _property_date:
.. include:: /reference/column/option/property.rst

Options
-------

.. _label_date:
.. |default_label| replace:: `""`
.. include:: /reference/column/option/label.rst

.. _format_date:
.. |default_format| replace:: `d.m.Y`
.. include:: /reference/column/option/format.rst

.. _translation_domain_date:
.. |default_translation_domain| replace:: `null`
.. include:: /reference/column/option/translationDomain.rst

.. _width_date:
.. |default_width| replace:: ``1``
.. include:: /reference/column/option/width.rst

.. _sortable_date:
.. |default_sortable| replace:: false
.. include:: /reference/column/option/sortable.rst

.. _condition_date:
.. |default_condition| replace:: null
.. include:: /reference/column/option/condition.rst

.. _sorting_property_date:
.. |default_sorting_property| replace:: null
.. include:: /reference/column/option/sortingProperty.rst

