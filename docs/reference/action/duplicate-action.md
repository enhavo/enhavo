## Duplicate

Duplicates the current resource and creates a new instance with the same
values.

<ReferenceTable
type="download"
className="Enhavo\Bundle\AppBundle\Action\Type\DuplicateActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="duplicate" :required="true" />
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

### route {#route_duplicate}

**type**: `string`

Define which route should be used to duplicate the selected resource.

``` yaml
actions:
    duplicate:
        type: duplicate
        route: my_duplicate_route
```
