## Input

An input control the input mask for creating and editing a resource.
You have to define all options similar to a grid.

```yaml
# config/resources/book.yaml
enhavo_resource:
    inputs:
        # name of the input
        app.book:
            # configuration options
            extends: enhavo_resource.input
            priority: 10
            overwrite: false
            # default input class (optional)
            class: Enhavo\Bundle\ResourceBundle\Input\Input
            # options of the input class
            resource: app.book
            form: App\Form\Type\BookType
            form_options: []
            actions: []
            actions_secondary: []
            tabs: []
            factory_method: createNew
            factory_arguments: []
            repository_method: find
            repository_arguments: [
                'expr:resource.getId()'
            ]
            serialization_groups: endpoint
            validation_groups: ['default']
```

### Routes

Admin routes.

```yaml
# config/routes/admin/book.yaml
app_admin_book_create:
    path: /book/create
    defaults:
        _expose: admin
        _vue:
            component: resource-input
            groups: admin
            meta:
                api: app_admin_api_create
        _endpoint:
            type: admin

app_admin_book_update:
    path: /book/update/{id}
    defaults:
        _expose: admin
        _vue:
            component: resource-input
            groups: admin
            meta:
                api: app_admin_api_update
        _endpoint:
            type: admin
```

Admin api routes.

```yaml
# config/routes/admin_api/book.yaml
app_admin_api_create:
    path: /book/create
    methods: [GET, POST]
    defaults:
        _expose: admin_api
        _endpoint:
            type: resource_create
            input: app.book

app_admin_api_update:
    path: /book/update/{id}
    methods: [GET, POST]
    defaults:
        _expose: admin_api
        _endpoint:
            type: resource_update
            input: app.book

```

### Tabs

Define tabs.

```yaml
enhavo_resource:
    inputs:
        app.book:
           tabs:
                main:
                    label: Book
                    type: form
                    arrangement: |
                        name
                        chapters

```

### Duplicate

Define the action button.

```yaml
enhavo_resource:
    inputs:
        app.book:
           actions:
                duplicate:
                    type: duplicate
                    enabled: 'expr:resource && resource.getId()'
```

Define the route.

```yaml
app_api_book_duplicate:
    path: /book/duplicate/{id}
    methods: [POST]
    defaults:
        _expose: admin_api
        _endpoint:
            type: resource_duplicate
            input: app.book
```

### Preview

Define the preview action.

```yaml
enhavo_resource:
    inputs:
        app.book:
           actions:
                preview:
                    type: preview
                    enabled: 'expr:resource && resource.getId()'
```

Define the route.

```yaml
app_admin_api_preview:
    path: /book/preview/{id}
    methods: [GET, POST]
    defaults:
        _expose: admin_api
        _area: theme
        _endpoint:
            type: preview
            input: app.book
            endpoint:
                type: App\Endpoint\BookEndpointType
                resource: expr:resource
                preview: true
```
