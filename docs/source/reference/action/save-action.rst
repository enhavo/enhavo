Save Action
===========

Submits the current form and updates the current resource form view.

.. csv-table::
    :widths: 50 150

    Type , save
    Require ,"- | :ref:`route <route_save>`"
    Options ,"- | :ref:`label <label_save>`
    - | :ref:`icon <icon_save>`
    - | :ref:`translation_domain <translation_domain_save>`
    - | :ref:`permission <permission_save>`
    - | :ref:`hidden <hidden_save>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\SaveActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType`

Require
-------

.. _route_preview:

route
~~~~~

**type**: `string`
**default**: `null`

Define the save route where to send the current form. If you leave that parameter, the form will send to the default
action of the form. If the passed resource has already an id, that id will also passed as parameter to the generate url.

.. code-block:: yaml

    buttons:
        save:
            type: save
            route: my_save_route

Options
-------

.. _label_save:
.. |default_label| replace:: `label.save`
.. include:: /reference/action/option/label.rst

.. _icon_save:
.. |default_icon| replace:: `save`
.. include:: /reference/action/option/icon.rst

.. _translation_domain_save:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _permission_save:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_save:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst

