.. _abstract-url-action:

Abstract Url Action
===================

AbstractUrlAction contains the AbstractAction and extends it by two additional parameters to redirect the user to a specific route.

.. csv-table::
    :widths: 50 150

    Require , "- | route_"
    Options , "- | :ref:`route_parameters <route_parameters>`"
    Inherited options, "- | :ref:`label <label>`
    - | :ref:`translation_domain <translation_domain>`
    - | :ref:`icon <icon>`
    - | :ref:`permission <permission>`
    - | :ref:`hidden <hidden>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\AbstractUrlActionType`
    Parent, :ref:`Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType <abstract-action>`

Require
-------

.. _route:

route
~~~~~

**type**: `string`
**default**: `null`

Define which route should be used for the create overlay.

.. code-block:: yaml

    actions:
        myAction:
            type: my_action_type
            route: my_create_route

Option
------

.. _route_parameters:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

Inherited option
------

.. _label:
.. |default_label| replace:: `null`
.. include:: /reference/action/option/label.rst

.. _translation_domain:
.. |default_translationDomain| replace:: `null`
.. include:: /reference/action/option/translationDomain.rst

.. _icon:
.. |default_icon| replace:: `null`
.. include:: /reference/action/option/icon.rst

.. _permission:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst