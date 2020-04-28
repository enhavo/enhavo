Template Column
===============

Renders specific template with a certain property of a resource.

.. csv-table::
    :widths: 50 150

    Type , template
    Require ,"- | :ref:`property <property_template>`"
    Options ,"- | :ref:`label <label_template>`
    - | :ref:`translation_domain <translation_domain_template>`
    - | :ref:`width <width_template>`
    - | :ref:`sortable <sortable_template>`
    - | :ref:`condition <condition_template>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Column\\Type\\TemplateType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Column\\AbstractColumnType`

Require
-------

.. _property_template:
.. include:: /reference/column/option/property.rst

Options
-------

.. _label_template:
.. |default_label| replace:: `""`
.. include:: /reference/column/option/label.rst

.. _translation_domain_template:
.. |default_translation_domain| replace:: `null`
.. include:: /reference/column/option/translationDomain.rst

.. _width_template:
.. |default_width| replace:: ``1``
.. include:: /reference/column/option/width.rst

.. _sortable_template:
.. |default_sortable| replace:: false
.. include:: /reference/column/option/sortable.rst

.. _condition_template:
.. |default_condition| replace:: null
.. include:: /reference/column/option/condition.rst


