Template Column
===============

Render specific template with a certain property of a resource.

+-------------+--------------------------------------------------------------------+
| type        | template                                                           |
+-------------+--------------------------------------------------------------------+
| require     | - property_                                                        |
|             | - template_                                                        |
+-------------+--------------------------------------------------------------------+
| option      | - width_                                                           |
|             | - label_                                                           |
|             | - translationDomain_                                               |
+-------------+--------------------------------------------------------------------+
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Table\\Column\\PropertyColumn`  |
+-------------+--------------------------------------------------------------------+

Require
-------

.. include:: /reference/column/option/property.rst


template
~~~~~~~~

**type**: `string`

Define the template that should be used for rendering.
The parameters ``value`` (value of property) and ``data`` (resource) will be passed as vars to the template.

.. code-block:: yaml

    buttons:
        myColumn:
            template: MyBundle:TableColumn:myTemplate.html.twig


Option
------

.. include:: /reference/column/option/width.rst

.. include:: /reference/column/option/label.rst

.. include:: /reference/column/option/translationDomain.rst

