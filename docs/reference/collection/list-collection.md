## List collection

###  Sorting

A resource might be sortable, meaning it has an order which can be
changed by the user. In this case, it needs two additional routes for
the events of moving the object to the new position.

The move_after route handles moving the item to the position after
another element, the move_to_page route allows moving an item to the top
or bottom of a pagination page.

Note: If you changed the pagination value in the table route, you need
to add the same value to the move_to_page route as well.

  -------------------------------- -------------------------------------------------
**Parameters**                   Description

**\_sylius.sortable_position**   Property of that model or row (int) which is used
for sorting

**\_sylius.paginate**            If set in table route, must be set to the same
value here

**viewer.sorting**               **desc** (default) means higher values come
before lower values, with 0 being the last
element; **asc** is the other way around
  -------------------------------- -------------------------------------------------

``` yaml
enhavo_page_page_move_after:
    options:
        expose: true
    path: /admin/enhavo/page/page/move-after/{id}/{target}
    methods: [POST]
    defaults:
        _controller: enhavo_page.controller.slide:moveAfterAction
        _sylius:
            sortable_position: position
            viewer:
                sorting: desc
```

``` yaml
enhavo_page_page_move_to_page:
    options:
        expose: true
    path: /admin/enhavo/page/page/move-to-page/{id}/{page}/{top}
    methods: [POST]
    defaults:
        _controller: enhavo_page.controller.slide:moveToPageAction
        _sylius:
            sortable_position: position
            viewer:
                sorting: desc
```
