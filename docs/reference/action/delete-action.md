## Delete

Deletes the current resource and closes the window.

<ReferenceTable
type="delete"
className="Enhavo\Bundle\AppBundle\Action\Type\DeleteActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="delete" :required="true" />
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

### route {#route_delete}

**type**: `string`

Define which route should be used to delete the selected resource.

``` yaml
actions:
    delete:
        type: delete
        route: my_delete_route
```

