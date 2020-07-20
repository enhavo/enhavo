Number Widget
============

The Number Widget shows a label and a number next to it.

.. csv-table::
    :widths: 50 150

    Type , number
    Required , "- | :ref:`provider <provider_number>`"
    Options , "- | :ref:`label <label_number>`"
    Class, :class:`Enhavo\\Bundle\\DashboardBundle\\Widget\\Type\\NumberWidgetType`
    Parent, :class:`Enhavo\\Bundle\\DashboardBundle\\Widget\\AbstractWidgetType`


Required
-------

.. _provider_number:

provider
~~~~~

**type**: `array`
**default**: `null`

Define which provider should be used.

.. code-block:: yaml

    widgets:
        myWidget:
            provider:
                type: myProviderType
                # ... further provider options
            # ... further widget options

Options
-------

.. _label_number:

label
~~~~~

**type**: `string`
**default**: `null`

Overwrite the default label. It will be translated over the translation service automatically (See translationDomain)

.. code-block:: yaml

    widgets:
        myWidget:
            label: myLabel
            # ... further options
