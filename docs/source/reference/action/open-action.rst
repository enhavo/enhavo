Open Action
===========

Opens the specified route in a separate tab in your browser. Can be used, for example, to view self-created newsletters, products, etc.

.. csv-table::
    :widths: 50 150

    Type , open
    Require ,"- | :ref:`route <route_open>`"
    Options ,"- | :ref:`target <target_open>`
    - | :ref:`label <label_open>`
    - | :ref:`icon <icon_open>`
    - | :ref:`translation_domain <translation_domain_open>`
    - | :ref:`permission <permission_open>`
    - | :ref:`hidden <hidden_open>`
    - | :ref:`route_parameters <route_parameters_open>`
    - | :ref:`view_key <view_key_open>`"
    - | :ref:`confirm <confirm_open>`"
    - | :ref:`confirm_message <confirm_message_open>`
    - | :ref:`confirm_label_ok <confirm_label_ok_open>`
    - | :ref:`confirm_label_cancel <confirm_label_cancel_open>`
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\OpenActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractUrlActionType`

Require
-------

.. _route_open:

route
~~~~~

**type**: `string`
**default**: `null`

Defines which route should be used to open the current resource

.. code-block:: yaml

    actions:
        open:
            type: open
            route: my_open_route

Options
-------

.. _target_open:

target
~~~~~~

**type**: `string`
**default**: `_self`

The target attribute specifies the target window base of a reference. If you use ``_view``, the target window will be
a new enhavo view.

.. code-block:: yaml

    actions:
        open:
            target: _targetOption


.. _label_open:
.. |default_label| replace:: `Open`
.. include:: /reference/action/option/label.rst

.. _icon_open:
.. |default_icon| replace:: `arrow_forward`
.. include:: /reference/action/option/icon.rst

.. _translation_domain_open:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _permission_open:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_open:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst

.. _route_parameters_open:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

.. _view_key_open:
.. |default_view_key| replace:: null
.. include:: /reference/action/option/view_key.rst

.. _confirm_open:
.. |default_confirm| replace:: null
.. include:: /reference/action/option/confirm.rst

.. _confirm_message_open:
.. |default_confirm_message| replace:: `message.delete.confirm`
.. include:: /reference/action/option/confirm_message.rst

.. _confirm_label_ok_open:
.. |default_confirm_label_ok| replace:: `label.ok`
.. include:: /reference/action/option/confirm_label_ok.rst

.. _confirm_label_cancel_open:
.. |default_confirm_label_cancel| replace:: `label.cancel`
.. include:: /reference/action/option/confirm_label_cancel.rst

