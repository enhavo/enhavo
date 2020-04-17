confirm_label_cancel
~~~~~~~~~~~~~~~~~~~~

**type**: `string`
**default**: |default_confirm_label_cancel|

Overwrites the default cancel-button lettering in the confirm modal window. It will be translated over the translation service automatically (See translationDomain)

.. code-block:: yaml

    actions:
        myAction:
            confirm_label_cancel: myMessage
            # ... further option