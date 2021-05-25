permission
~~~~~~~~~~

**type**: `string|null`

Symfony security role required for the user to be able to see and use the filter. Default is `null`.

.. code-block:: yaml

    columns:
        myFilter:
            permission: ROLE_ENHAVO_ARTICLE_ARTICLE_EDIT
