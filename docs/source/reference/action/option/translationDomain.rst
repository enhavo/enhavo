translationDomain
~~~~~

**type**: `string`
**default**: |default_translationDomain|

Overwrite the default translationDomain. The selected bundle implements a translation service for automatic translation
all translatable designations, e.g. the label

.. code-block:: yaml

    actions:
        myAction:
            translationDomain: myTranslationDomain
            # ... further option