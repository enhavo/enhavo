Preview Action
==============

Opens the integrated preview window with three different display modes for desktop, mobile and tablet.

.. csv-table::
    :widths: 50 150

    Type , preview
    Require ,"- | :ref:`route <route_preview>`"
    Options ,"- | :ref:`label <label_preview>`
    - | :ref:`icon <icon_preview>`
    - | :ref:`translation_domain <translation_domain_preview>`
    - | :ref:`permission <permission_preview>`
    - | :ref:`hidden <hidden_preview>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\PreviewActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType`


Require
-------

.. _route_preview:

route
~~~~~

**type**: `string`
**default**: `null`

Defines which preview route should be used to open the preview view.

.. code-block:: yaml

    actions:
        preview:
            type: preview
            route: my_preview_route

Options
-------

.. _label_preview:
.. |default_label| replace:: `label.preview`
.. include:: /reference/action/option/label.rst

.. _icon_preview:
.. |default_icon| replace:: `remove_red_eye`
.. include:: /reference/action/option/icon.rst

.. _translation_domain_preview:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _permission_preview:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_preview:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst











