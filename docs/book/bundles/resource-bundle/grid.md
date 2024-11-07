## Grid

A grid is a list of resources. The resource bundle allow highly customized grids.
This means, you can redefine your own grid or customize the default one.
It comes with a lot of options and subcomponents to fit most of your needs.

Define your grid under `enhavo_resource.grids`.

```yaml
# config/resources/book.yaml
enhavo_resource:
    grids:
        # name of the grid
        app.book: 
            # configuration options
            extends: app.parent_grid
            priority: 10
            overwrite: false
            # default grid class (optional)
            class: Enhavo\Bundle\ResourceBundle\Grid\Grid
            # options of the default grid class
            resource: app.book # resource name (mandatory)
            component: 'grid-grid'
            routes: {} # route options
            collection: {} # collection options
            actions: {} # list of actions
            filters: {} # list of filters
            batches: {}  # list of batches
            columns: {} # list of columns
```

### Configuration control

The options `extends`, `priority` and `overwrite` are not part of the grid class.
These options controlling the handling to other configured grids.

If multiple grids defined with the same name, the `priority` options control the order. Not all 
options will be overwritten. For example, if you define a action in the parent and another in the child grid,
both actions will be used. If you use `overwrite` with value `true` in the child, all actions will be
overwritten and only the actions in the child definition are used.

With `extends`, you will inherit the grid class and options from that specified grid.

### Grid class and options

By default, the class `Enhavo\Bundle\ResourceBundle\Grid\Grid` is used and don't need to be defined
if you stick to it.

Further options depends on the grid class and it's subsystems.

In the default grid the name is not automatically connected to the resource name. 
So you are able to define multiple grids for a single resource.

### Routing and flow

An admin route is needed to display the `resource-index` component. 

```yaml
# config/routes/admin/book.yaml
app_admin_book_index:
    path: /book/index
    defaults:
        _expose: admin
        _vue:
            component: resource-index
            groups: admin
            meta:
                api: app_admin_api_book_index
        _endpoint:
            type: admin
```

This component will use the api route, which passed over meta and fetch the configuration from this endpoint.
Inside the configuration, we find all infos for the subcomponent. 
The collection subcomponent get a new url to fetch the data for the grid.

```yaml
# config/routes/admin_api/book.yaml
app_admin_api_book_index:
    path: /book/index
    methods: [GET]
    defaults:
        _expose: admin_api
        _endpoint:
            type: resource_index
            grid: app.book

app_admin_api_book_list:
    path: /book/list
    methods: [GET,POST]
    defaults:
        _expose: admin_api
        _endpoint:
            type: resource_list
            grid: app.book

app_admin_api_book_batch:
    path: /book/batch
    methods: [POST]
    defaults:
        _expose: admin_api
        _endpoint:
            type: resource_batch
            grid: app.book
```

### Collection

```yaml
enhavo_resource:
    grids:
        app.book: 
            collection:
                class: Enhavo\Bundle\ResourceBundle\Collection\TableCollection
                limit:  100,
                paginated: true,
                repository_method: ~
                repository_arguments: ~
                pagination_steps: [5, 10, 50, 100, 500, 1000]
                component: 'collection-table'
                model: 'TableCollection'
                filters: []
                sorting: []
                criteria: []
```

### Action

Actions are like buttons. If you click on one of these buttons,
something will be executed. For example an overlay could be displayed etc.

### Filter

tbc.


### Batch

Batch are like actions, that can be
run on multiple items from the table view by selecting the items and
choosing an action from a dropdown menu.
