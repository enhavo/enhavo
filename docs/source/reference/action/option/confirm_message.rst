confirm_message
~~~~~~~~~~~~~~~

**type**: `string`
**default**: |default_confirm_message|

Overwrites the default message in the confirm modal window. It will be translated over the translation service automatically (See translationDomain)

.. code-block:: yaml

    actions:
        myAction:
            confirm_message: myMessage
            # ... further option