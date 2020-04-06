Delete Action
=============

Deletes the current resource and closes the window.

.. csv-table::
    :widths: 50 150

    Type , delete
    Require , "- | route_"
    Options ,"- | :ref:`confirm <confirm>`
    - | :ref:`confirm_message <confirm_message>`
    - | :ref:`confirm_label_ok <confirm_label_ok>`
    - | :ref:`confirm_label_cancel <confirm_label_cancel>`"
    Inherited options, "- | :ref:`route_parameters <route_parameters>`
    - | :ref:`label <label>`
    - | :ref:`translation_domain <translation_domain>`
    - | :ref:`icon <icon>`
    - | :ref:`permission <permission>`
    - | :ref:`hidden <hidden>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\DeleteActionType`
    Parent, :ref:`Enhavo\\Bundle\\AppBundle\\Action\\AbstractUrlActionType <abstract-url-action>`


Require
-------

.. _route:

route
~~~~~

**type**: `string`
**default**: `null`

Define which route should be used to delete the selected resource.

.. code-block:: yaml

    actions:
        delete:
            type: delete
            route: my_delete_route

Options
-------

.. _confirm:
.. |default_confirm| replace:: `true`
.. include:: /reference/action/option/confirm.rst

.. _confirm_message:
.. |default_confirm_message| replace:: `message.delete.confirm`
.. include:: /reference/action/option/confirm_message.rst

.. _confirm_label_ok:
.. |default_confirm_label_ok| replace:: `label.ok`
.. include:: /reference/action/option/confirm_label_ok.rst

.. _confirm_label_cancel:
.. |default_confirm_label_cancel| replace:: `label.cancel`
.. include:: /reference/action/option/confirm_label_cancel.rst

Inherited Option
----------------

.. _route_parameters:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

.. _label:
.. |default_label| replace:: `label.delete`
.. include:: /reference/action/option/label.rst

.. _translation_domain:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon:
.. |default_icon| replace:: `delete`
.. include:: /reference/action/option/icon.rst

.. _permission:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst