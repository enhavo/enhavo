## App Route

Before you use the app route, you should know what is meant by `actions`
and `blocks`. The app route is used to display these two kinds of
abstractions. It is independent of any data model.

In this Picture you can see how the app route could render `actions` and
`blocks`.

![image](/images/admin-wireframe.png)

### Actions

Actions are things like buttons. If you click on one of these buttons,
something will be executed. For example an overlay could be displayed,
which provides a form for the user to change data.

### Blocks

A block is a small unit with its own logic. Its main function is to
present some information, but it could also contain clickable events.

### Route

Here is an example route

```yaml
enhavo_page_page_app:
    path: /enhavo/page/page/app
    methods: [GET]
    defaults:
        _controller: enhavo_page.controller.page:indexAction
        _sylius:
            template: EnhavoAppBundle:App:index.html.twig
            viewer:
                type: app
                parameters:
                    name: value
                blocks:
                    table:
                        type: enhavo_page_page_table
                        parameters:
                            table_route: enhavo_page_page_table
                            update_route: enhavo_page_page_update
                actions:
                    create:
                        type: overlay
                        route: enhavo_page_page_create
                        icon: plus
                        label: label.create
```
