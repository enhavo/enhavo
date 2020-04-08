Output Stream Action
====================

This action is a specified version of the :ref:`modal-action <modal_action>`. The route defines the controller action that generates
the output stream of the current resource.


.. csv-table::
    :widths: 50 150

    Type , output_stream
    Require ,"- | :ref:`route <route_output_stream>`"
    Options ,"- | :ref:`route_parameters <route_parameters_output_stream>`
    - | :ref:`label <label_output_stream>`
    - | :ref:`icon <icon_output_stream>`
    - | :ref:`translation_domain <translation_domain_output_stream>`
    - | :ref:`permission <permission_output_stream>`
    - | :ref:`hidden <hidden_output_stream>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\OutputStreamActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractUrlActionType`

Require
-------

.. _route_output_stream:

route
~~~~~

**type**: `string`
**default**: `null`

The route defines the action that generates the output stream of the current resource.

.. code-block:: yaml

    actions:
        output_stream:
            type: output_stream
            route: my_output_stream_route

Options
-------

.. _route_parameters_output_stream:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

.. _label_output_stream:
.. |default_label| replace:: `null`
.. include:: /reference/action/option/label.rst

.. _translation_domain_output_stream:
.. |default_translationDomain| replace:: `null`
.. include:: /reference/action/option/translationDomain.rst

.. _icon_output_stream:
.. |default_icon| replace:: `null`
.. include:: /reference/action/option/icon.rst

.. _permission_output_stream:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_output_stream:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst















