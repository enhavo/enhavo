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

.. include:: /reference/button/option/label.rst

.. include:: /reference/button/option/icon.rst

.. include:: /reference/button/option/translationDomain.rst

.. include:: /reference/button/option/display.rst

.. include:: /reference/button/option/role.rst

