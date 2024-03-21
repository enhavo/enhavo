## Filter

Opens an additional view that contains all available filters of a table.

<ReferenceTable
type="event"
className="Enhavo\Bundle\AppBundle\Action\Type\EventActionType"
>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="icon" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="hidden" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="view_key" />
</template>
</ReferenceTable>

Filters must be added under `my_entity_table`. By default, this action
is only available for the index window which is represented by the
`my_entity_index` route. How to create or add custom filters is
described `here <add_custom_filter>`{.interpreted-text role="ref"} in
more detail.

``` yaml
app_entity_index:
    options:
        expose: true
    path: /app/entity/index
    methods: [GET]
    defaults:
        _controller: app.controller.entity:indexAction
        _sylius:
            viewer:
                actions:
                    filter:
                        type: filter

app_entity_table:
    options:
        expose: true
    path: /app/entity/table
    methods: [GET,POST]
    defaults:
        _controller: app.controller.entity:tableAction
        _sylius:
            filters:
                my_filter:
                    type: property_type
                    property: property
```

