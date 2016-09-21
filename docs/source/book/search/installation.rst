Installation
============

Just copy the following code into your ``enhavo.yml``

.. code-block:: yml

    enhavo_search:
        search:
            template: EnhavoSearchBundle:Search:render.html.twig
            strategy: index
            search_engine: enhavo_search_search_engine
            index_engine: enhavo_search_index_engine

Here you tell enhavo which template should be rendered for the search-field,
which indexing strategy should be used and which engines do the work of indexing and searching.

