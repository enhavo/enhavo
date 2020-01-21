Preview Action
==============

Send the current state of the form to a preview route that will display the view in a separate overview.

+-------------+--------------------------------------------------------------------+
| type        | preview                                                            |
+-------------+--------------------------------------------------------------------+
| require     | - route_                                                           |
+-------------+--------------------------------------------------------------------+
| option      | - label_                                                           |
|             | - icon_                                                            |
|             | - translationDomain_                                               |
|             | - display_                                                         |
|             | - role_                                                            |
+-------------+--------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Button\\Button\\PreviewButton`  |
+-------------+--------------------------------------------------------------------+



Require
-------

route
~~~~~

**type**: `string`

Define the preview route where to send the current form.

.. code-block:: yaml

    buttons:
        preview:
            type: preview
            route: my_preview_route

Option
------

.. include:: /reference/action/option/label.rst

.. include:: /reference/action/option/icon.rst

.. include:: /reference/action/option/translationDomain.rst

.. include:: /reference/action/option/display.rst

.. include:: /reference/action/option/role.rst

