Update Action
=============

Opens a window in which the selected resource can be edited. Can be used in a table column if the default
opening route is used for something else.

.. csv-table::
    :widths: 50 150

    Type , update
    Require ,"- | :ref:`route <route_update>`"
    Options ,"- | :ref:`label <label_update>`
    - | :ref:`icon <icon_update>`
    - | :ref:`translation_domain <translation_domain_update>`
    - | :ref:`permission <permission_update>`
    - | :ref:`hidden <hidden_update>`
    - | :ref:`route_parameters <route_parameters_update>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\UpdateActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractUrlActionType`

Require
-------

.. _route_update:

route
~~~~~

**type**: `string`
**default**: `null`

Defines which update route should be used to edit the selected resource.

.. code-block:: yaml

    actions:
        update:
            type: update
            route: my_ressource_update_route

Options
-------

.. _label_update:
.. |default_label| replace:: `label.edit`
.. include:: /reference/action/option/label.rst

.. _icon_update:
.. |default_icon| replace:: `edit`
.. include:: /reference/action/option/icon.rst

.. _translation_domain_update:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _permission_update:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_update:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst

.. _route_parameters_update:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst









