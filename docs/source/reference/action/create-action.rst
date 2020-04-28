Create Action
=============

The CreateAction represents a create button for a specific route.

.. csv-table::
    :widths: 50 150

    Type , create
    Require , "- | :ref:`route <route_create>`"
    Options ,"- | :ref:`route_parameters <route_parameters_create>`
    - | :ref:`label <label_create>`
    - | :ref:`translation_domain <translation_domain_create>`
    - | :ref:`icon <icon_create>`
    - | :ref:`permission <permission_create>`
    - | :ref:`view_key <view_key_create>`
    - | :ref:`hidden <hidden_create>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\CreateActionType`
    Parent, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\OpenActionType`

Require
-------

.. _route_create:

route
~~~~~

**type**: `string`
**default**: `null`

Define which route should be used for the create overlay.

.. code-block:: yaml

    actions:
        create:
            type: create
            route: my_create_route

Options
-------

.. _route_parameters_create:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

.. _label_create:
.. |default_label| replace:: `label.create`
.. include:: /reference/action/option/label.rst

.. _translation_domain_create:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon_create:
.. |default_icon| replace:: `add_circle_outline`
.. include:: /reference/action/option/icon.rst

.. _permission_create:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_create:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst

.. _view_key_create:
.. |default_view_key| replace:: 'edit-view'
.. include:: /reference/action/option/view_key.rst
