## Remove batch action

All bundles have the batch action *delete* by default. But maybe we
don\'t want the user to be able to delete the resource via batch action.
Or we don\'t want to use batch actions at all.

So here we will see different ways of removing the default delete batch
action, or all batch actions.

### 1. Configure route

The batch action *delete* is part of the default settings of the table
routes setting *\_viewer.table.batch_actions*. If we don\'t want the
default setting, we just have to redefine this setting as empty.

``` yaml
enhavo_page_page_table:
    ...
    defaults:
        ...
        _viewer:
            ...
            table:
                batch_actions:          # No children, so the result is an empty array
                columns:
                    ...
```

### 2. Security roles

The batch action *delete* needs the security permission *delete* to
delete the resource. If the user doesn\'t have this permission, the
action will not be displayed. Of course he won\'t be able to delete the
resource via the *delete* button either.

### 3. Remove batch action route

If the route for batch actions (default: \_batch) isn\'t defined for the
resource, the batch actions won\'t be available either. So if we don\'t
want any batch actions for our resource, we just don\'t add this route
to its routing.
