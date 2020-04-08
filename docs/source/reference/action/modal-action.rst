.. _modal_action:
Modal Action
============

[Explanation]


.. csv-table::
    :widths: 50 150

    Type , modal
    Require , "- | :ref:`modal <modal_modal>`"
    Options , "- | :ref:`label <label_modal>`
    - | :ref:`icon <icon_modal>`
    - | :ref:`translation_domain <translation_domain_modal>`
    - | :ref:`permission <permission_modal>`
    - | :ref:`hidden <hidden_modal>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\ModalActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType`


Basic Usage
-----------

[Explanation]

Require
-------

.. _modal_modal:

modal
~~~~~

**type**: `string`
**default**: `null`

[Explanation]

.. code-block:: yaml

    actions:
        modal:
            type: modal
            modal: myModal

Options
-------

.. _label_modal:
.. |default_label| replace:: `null`
.. include:: /reference/action/option/label.rst

.. _icon_modal:
.. |default_icon| replace:: `null`
.. include:: /reference/action/option/icon.rst

.. _translation_domain_modal:
.. |default_translationDomain| replace:: `null`
.. include:: /reference/action/option/translationDomain.rst

.. _permission_modal:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_modal:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst















