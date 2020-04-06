Download Action
===============

Downloads the current resource. The Resource must implement the :class:Enhavo\\Bundle\\MediaBundle\\Model\\FileInterface`

.. csv-table::
    :widths: 50 150

    Type , download
    Require , "- | route_"
    Options ,"- | :ref:`ajax <ajax>`"
    Inherited options, "- | :ref:`route_parameters <route_parameters>`
    - | :ref:`label <label>`
    - | :ref:`translation_domain <translation_domain>`
    - | :ref:`icon <icon>`
    - | :ref:`permission <permission>`
    - | :ref:`hidden <hidden>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\DownloadActionType`
    Parent, :ref:`Enhavo\\Bundle\\AppBundle\\Action\\AbstractUrlActionType <abstract-url-action>`


Require
-------

.. _route:

route
~~~~~

**type**: `string`
**default**: `null`

Defines which route should be used to download the selected resource.

.. code-block:: yaml

    actions:
        download:
            type: download
            route: my_download_route

Options
-------

.. _ajax:
**type**: `boolean`
**default**: `false`

If the value is true, the download request call is executed via "Ajax"-Call in the background.

.. code-block:: yaml

    actions:
        download:
            type: download
            route: my_download_route

Inherited Option
----------------

.. _route_parameters:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

.. _label:
.. |default_label| replace:: `label.download`
.. include:: /reference/action/option/label.rst

.. _translation_domain:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon:
.. |default_icon| replace:: `file_download`
.. include:: /reference/action/option/icon.rst

.. _permission:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst


