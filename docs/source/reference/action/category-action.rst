CategoryAction
==============

The CategoryAction provide a small overlay to manage categories quickly.

+-------------+--------------------------------------------------------------------+
| type        | category                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - label_                                                           |
|             | - collection_                                                      |
|             | - route_                                                           |
|             | - icon_                                                            |
|             | - translationDomain_                                               |
+-------------+--------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\CategoryBundle\\Action\\CategoryAction`    |
+-------------+--------------------------------------------------------------------+



Option
------

.. _collection:

collection
~~~~~~~~~~

**type**: `string`

Define which collection should be used for the categories. If no one is set, the default category collection will be used.

.. code-block:: yaml

    actions:
        category:
            type: category
            collection: label

.. include:: /reference/action/option/route.rst

.. include:: /reference/action/option/label.rst

.. include:: /reference/action/option/icon.rst

.. include:: /reference/action/option/translationDomain.rst














