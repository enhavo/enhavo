## Open

Opens the specified route in a separate tab in your browser. Can be
used, for example, to view self-created newsletters, products, etc.

<ReferenceTable
type="open"
className="Enhavo\Bundle\AppBundle\Action\Type\OpenActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="open" :required="true" />
    <ReferenceOption name="target" type="open" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="route_parameters" />,
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="hidden" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="view_key" />,
    <ReferenceOption name="confirm" />,
    <ReferenceOption name="confirm_message" />,
    <ReferenceOption name="confirm_label_ok" />,
    <ReferenceOption name="confirm_label_cancel" />
</template>
</ReferenceTable>

### route {#route_open}

**type**: `string`

Defines which route should be used to open the current resource

``` yaml
actions:
    open:
        type: open
        route: my_open_route
```

### target {#target_open}

**type**: `string`

The target attribute specifies the target window base of a reference. If
you use `_view`, the target window will be a new enhavo view.

``` yaml
actions:
    open:
        target: _targetOption
```
