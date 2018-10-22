SaveButton
==========

Will submit the current form and close the overlay.

+-------------+--------------------------------------------------------------------+
| type        | save                                                               |
+-------------+--------------------------------------------------------------------+
| option      | - label_                                                           |
|             | - route_                                                           |
|             | - routeParameters_                                                 |
|             | - icon_                                                            |
|             | - translationDomain_                                               |
|             | - display_                                                         |
|             | - role_                                                            |
+-------------+--------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Button\\Button\\SaveButton`     |
+-------------+--------------------------------------------------------------------+


Option
------

route
~~~~~

**type**: `string`

Define the save route where to send the current form. If you leave that parameter, the form will send to the default
action of the form. If the passed resource has already an id, that id will also passed as parameter to the generate url.

.. code-block:: yaml

    buttons:
        save:
            type: save
            route: my_save_route

.. include:: /reference/button/option/routeParameters.rst

.. include:: /reference/button/option/label.rst

.. include:: /reference/button/option/icon.rst

.. include:: /reference/button/option/translationDomain.rst

.. include:: /reference/button/option/display.rst

.. include:: /reference/button/option/role.rst

