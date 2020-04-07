Event Action
============

Duplicates the current resource and creates a new instance with the same values.

.. csv-table::
    :widths: 50 150

    Type , event
    Require , "- | :ref:`label <label_event>`
    - | :ref:`icon <icon_event>`
    - | :ref:`event <event_event>`"
    Options ,"- | :ref:`translation_domain <translation_domain_event>`
    - | :ref:`permission <permission_event>`
    - | :ref:`hidden <hidden_event>`
    - | :ref:`confirm <confirm_event>`
    - | :ref:`confirm_changes <confirm_changes_event>`
    - | :ref:`confirm_message <confirm_message_event>`
    - | :ref:`confirm_label_ok <confirm_label_ok_event>`
    - | :ref:`confirm_label_cancel <confirm_label_cancel_event>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\EventActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType`


Require
-------

.. _label_event:
.. |default_label| replace:: ` `
.. include:: /reference/action/option/label.rst

.. _icon_event:
.. |default_icon| replace:: ` `
.. include:: /reference/action/option/icon.rst



event
~~~~~
.. _event_event:

**type**: `string`

Define which route should be used to duplicate the selected resource.

.. code-block:: yaml

    actions:
        event:
            type: event
            event: my_event

Options
-------

.. _confirm_event:
.. |default_confirm| replace:: `false`
.. include:: /reference/action/option/confirm.rst

.. _confirm_changes_event:
.. |default_confirm_changes| replace:: `true`
.. include:: /reference/action/option/confirm_changes.rst

.. _confirm_message_event:
.. |default_confirm_message| replace:: `message.close.confirm`
.. include:: /reference/action/option/confirm_message.rst

.. _confirm_label_ok_event:
.. |default_confirm_label_ok| replace:: `label.ok`
.. include:: /reference/action/option/confirm_label_ok.rst

.. _confirm_label_cancel_event:
.. |default_confirm_label_cancel| replace:: `label.cancel`
.. include:: /reference/action/option/confirm_label_cancel.rst

.. _route_parameters_event:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

.. _translation_domain_event:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _permission_event:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_event:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst



