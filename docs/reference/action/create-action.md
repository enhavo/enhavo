## Create

The CreateAction represents a create button for a specific route.

<ReferenceTable
type="create"
className="Enhavo\Bundle\AppBundle\Action\Type\CreateActionType"
parent="Enhavo\Bundle\AppBundle\Action\Type\OpenActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="create" :required="true" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="route_parameters" />,
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="hidden" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="view_key" />
</template>
</ReferenceTable>


### route {#route_create}

**type**: `string`

Define which route should be used for the create overlay.

``` yaml
actions:
    create:
        type: create
        route: my_create_route
```

