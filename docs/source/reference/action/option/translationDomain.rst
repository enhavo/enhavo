translation_domain
~~~~~~~~~~~~~~~~~~

**type**: `string`
**default**: |default_translationDomain|

Overwrites the default translationDomain. The selected bundle implements a translation service for automatic translation
all translatable designations, e.g. the label

.. code-block:: yaml

    actions:
        myAction:
            translation_domain: myTranslationDomain
            # ... further option