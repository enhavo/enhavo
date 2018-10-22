DownloadAction
==============

The DownloadAction represents a button for a specific route to download its target

+-------------+--------------------------------------------------------------------+
| type        | download                                                           |
+-------------+--------------------------------------------------------------------+
| require     | - route_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - label_                                                           |
|             | - icon_                                                            |
|             | - translationDomain_                                               |
+-------------+--------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Action\\Action\\DownloadAction` |
+-------------+--------------------------------------------------------------------+



Require
-------

route
~~~~~

**type**: `string`

Define which route should be used for download

.. code-block:: yaml

    actions:
        create:
            type: download
            route: my_download_route


Option
------

.. include:: /reference/action/option/label.rst

.. include:: /reference/action/option/icon.rst

.. include:: /reference/action/option/translationDomain.rst














