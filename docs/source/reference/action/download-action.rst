Download Action
===============

Downloads the current resource. The Resource must implement the :class:Enhavo\\Bundle\\MediaBundle\\Model\\FileInterface`

.. csv-table::
    :widths: 50 150

    Type , download
    Require , "- | :ref:`route <route_download>`"
    Options ,"- | :ref:`ajax <ajax_download>`
    - | :ref:`route_parameters <route_parameters_download>`
    - | :ref:`label <label_download>`
    - | :ref:`translation_domain <translation_domain_download>`
    - | :ref:`icon <icon_download>`
    - | :ref:`permission <permission_download>`
    - | :ref:`hidden <hidden_download>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\DownloadActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractUrlActionType`


Require
-------

.. _route_download:

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

.. _ajax_download:

**type**: `boolean`
**default**: `false`

If the value is true, the download request call is executed via "Ajax"-Call in the background.

.. code-block:: yaml

    actions:
        download:
            type: download
            route: my_download_route

.. _route_parameters_download:
.. |default_route_parameters| replace:: []
.. include:: /reference/action/option/routeParameters.rst

.. _label_download:
.. |default_label| replace:: `label.download`
.. include:: /reference/action/option/label.rst

.. _translation_domain_download:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon_download:
.. |default_icon| replace:: `file_download`
.. include:: /reference/action/option/icon.rst

.. _permission_download:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_download:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst


