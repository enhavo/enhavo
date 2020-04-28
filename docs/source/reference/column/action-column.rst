Action Column
=============

This column type allows the user to integrate any :ref:`action <action>` including all its functionality in this column.

.. csv-table::
    :widths: 50 150

    Type , action
    Require ,"- | :ref:`action <action_action>`"
    Options ,"- | :ref:`label <label_action>`
    - | :ref:`translation_domain <translation_domain_action>`
    - | :ref:`width <width_action>`
    - | :ref:`sortable <sortable_action>`
    - | :ref:`condition <condition_action>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Column\\Type\\ActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Column\\AbstractColumnType`

Require
-------

.. _action_action:

action
~~~~~~

**type**: `string`
**default**: []

The action to be placed in the column. Take a look to all possible actions :ref:`here <action>`.

.. code-block:: yaml

    columns:
        myColumn:
            type: action
            action:
                type: myAction
                route: my_action_route
                # ... further action options
            # ... further column options

Options
-------

.. _label_action:
.. |default_label| replace:: `""`
.. include:: /reference/column/option/label.rst

.. _translation_domain_action:
.. |default_translation_domain| replace:: `null`
.. include:: /reference/column/option/translationDomain.rst

.. _width_action:
.. |default_width| replace:: ``1``
.. include:: /reference/column/option/width.rst

.. _sortable_action:
.. |default_sortable| replace:: false
.. include:: /reference/column/option/sortable.rst

.. _condition_action:
.. |default_condition| replace:: null
.. include:: /reference/column/option/condition.rst


