Delete Route
============

.. note::

  This article outdated and may contain information that are not in use any more

.. code-block:: yaml

    enhavo_page_page_delete:
        path: /admin/enhavo/page/page/delete/{id}
        defaults:
            _controller: enhavo_page.controller.page:deleteAction
