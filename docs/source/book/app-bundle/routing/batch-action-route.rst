Batch action route
==================

This route is needed for batch actions. These are actions that can be run on multiple items from the table view by
selecting the items and choosing an action from a dropdown menu. If this route is not defined, the batch action
checkboxes and dropdown menu will not be visible.

.. code-block:: yaml

    enhavo_page_page_batch:
        options:
            expose: true
        path: /enhavo/page/page/batch
        methods: [POST]
        defaults:
            _controller: enhavo_page.controller.page:batchAction

Only this one route is needed for any number of batch actions. `See Guides on how to add a custom batch action`_.

.. _See guides on how to add a custom batch action: /guides/batchActions/add-batch-action.html
