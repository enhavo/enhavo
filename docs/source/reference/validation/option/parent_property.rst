parentProperty
~~~~~~~~~~~~~~

**type**: boolean
**default**: |default_confirm_changes|

If this value is true, the form registers changes made and the user must confirm that he wants to continue despite the
changes made

.. code-block:: yaml

    actions:
        myAction:
            confirm_changes: true|false
            # ... further option
