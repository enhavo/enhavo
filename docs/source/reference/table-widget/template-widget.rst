TemplateWidget
==============

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
| class       | :class:`Enhavo\\Bundle\\AppBundle\\Table\\Widget\\PropertyWidget`  |
+-------------+--------------------------------------------------------------------+

Require
-------

.. include:: /reference/table-widget/option/property.rst


template
~~~~~~~~

**type**: `string`

Define the template that should be used for rendering.
The parameters ``value`` (value of property) and ``data`` (resource) will be passed as vars to the template.

.. code-block:: yaml

    buttons:
        myWidget:
            template: MyBundle:TableWidget:myTemplate.html.twig


Option
------

.. include:: /reference/table-widget/option/width.rst

.. include:: /reference/table-widget/option/label.rst

.. include:: /reference/table-widget/option/translationDomain.rst

