Duplicate Action
================

Duplicates the current resource and creates a new instance with the same values.

.. csv-table::
    :widths: 50 150

    Type , delete
    Require , "- | :ref:`route <route_duplicate>`"
    Options ,"- | :ref:`label <label_duplicate>`
    - | :ref:`translation_domain <translation_domain_duplicate>`
    - | :ref:`icon <icon_duplicate>`
    - | :ref:`permission <permission_duplicate>`
    - | :ref:`hidden <hidden_duplicate>`
    - | :ref:`route_parameters <route_parameters_duplicate>`
    - | :ref:`confirm <confirm_duplicate>`
    - | :ref:`confirm_message <confirm_message_duplicate>`
    - | :ref:`confirm_label_ok <confirm_label_ok_duplicate>`
    - | :ref:`confirm_label_cancel <confirm_label_cancel_duplicate>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\DeleteActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractUrlActionType`


Require
-------

.. _route_duplicate:

route
~~~~~

**type**: `string`
**default**: `null`

Define which route should be used to duplicate the selected resource.

.. code-block:: yaml

    actions:
        duplicate:
            type: duplicate
            route: my_duplicate_route

Options
-------


.. _label_duplicate:
.. |default_label| replace:: `label.duplicate`
.. include:: /reference/action/option/label.rst

.. _translation_domain_duplicate:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon_duplicate:
.. |default_icon| replace:: `content_copy`
.. include:: /reference/action/option/icon.rst

.. _permission_duplicate:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_duplicate:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst

.. _route_parameters_duplicate:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

.. _confirm_duplicate:
.. |default_confirm| replace:: `true`
.. include:: /reference/action/option/confirm.rst

.. _confirm_message_duplicate:
.. |default_confirm_message| replace:: `message.duplicate.confirm`
.. include:: /reference/action/option/confirm_message.rst

.. _confirm_label_ok_duplicate:
.. |default_confirm_label_ok| replace:: `label.ok`
.. include:: /reference/action/option/confirm_label_ok.rst

.. _confirm_label_cancel_duplicate:
.. |default_confirm_label_cancel| replace:: `label.cancel`
.. include:: /reference/action/option/confirm_label_cancel.rst




