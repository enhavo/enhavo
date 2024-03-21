## Index Route

The index route is an implementation of the standard app route bound to
a resource. It contains default settings for the standard Enhavo layout
of resource views while still being as flexible and extendable as the
app route.

Here you can see the minimum route definition, which will render the
standard Enhavo layout by solely using default settings:

```yaml
enhavo_page_page_index:
    path: /enhavo/page/page/index
    methods: [GET]
    defaults:
        _controller: enhavo_page.controller.page:indexAction
        _sylius:
            template: EnhavoAppBundle:Resource:index.html.twig
```

This is really short, isn\'t it? Here you can see it with all the
default values spelled out:

```yaml
enhavo_page_page_index:
    path: /enhavo/page/page/index
    methods: [GET]
    defaults:
        _controller: enhavo_page.controller.page:indexAction
        _sylius:
            template: EnhavoAppBundle:Resource:index.html.twig
            viewer:
                type: index
                blocks:
                    table:
                        type: table
                        table_route: enhavo_page_page_table
                        update_route: enhavo_page_page_update
                actions:
                    create:
                        type: create
                        route: enhavo_page_page_create
```
