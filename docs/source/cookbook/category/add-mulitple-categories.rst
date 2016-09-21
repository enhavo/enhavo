Add multiple categories
=======================

Routing example for a collection that called `label`.

.. code:: yaml

    #routing
    enhavo_category_label_index:
        options:
            expose: true
        path: /enhavo/category/label/index
        methods: [GET]
        defaults:
            _controller: enhavo_category.controller.category:indexAction
            _sylius:
                template: EnhavoAppBundle:Resource:index.html.twig
            _viewer:
                title: category.label.category
                translationDomain: EnhavoCategoryBundle
                actions:
                    create:
                        type: create
                        route: enhavo_category_label_create
                blocks:
                    table:
                        type: table
                        table_route: enhavo_category_label_table

    enhavo_category_label_create:
        options:
            expose: true
        path: /enhavo/category/label/create
        methods: [GET,POST]
        defaults:
            _controller: enhavo_category.controller.category:createAction
            _sylius:
                template: EnhavoAppBundle:Resource:create.html.twig
                factory:
                    method: createWithCollection
                    arguments:
                        collection: label
            _viewer:
                translationDomain: EnhavoCategoryBundle
                buttons:
                    save:
                        route: enhavo_category_label_create
                tabs:
                    category:
                        label: category.label.category
                        template: EnhavoCategoryBundle:Tab:category.html.twig

    enhavo_category_label_table:
        options:
            expose: true
        path: /enhavo/category/label/table/{page}
        methods: [GET]
        defaults:
            page: 1
            _controller: enhavo_category.controller.category:tableAction
            _sylius:
                template: EnhavoAppBundle:Resource:table.html.twig
                repository:
                    method: findByCollection
                criteria:
                    collection: label
                    paging: true
            _viewer:
                translationDomain: EnhavoCategoryBundle
                table:
                    width: 12
                    columns:
                        id:
                            label: category.label.id
                            property: id
                        name:
                            width: 11
                            label: category.label.name
                            property: name

