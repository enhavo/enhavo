## Update

Opens a window in which the selected resource can be edited. Can be used
in a table column if the default opening route is used for something
else.

<ReferenceTable
type="update"
className="Enhavo\Bundle\AppBundle\Action\Type\UpdateActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="save" :required="true" />
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

### route {#route_update}

**type**: `string`

Defines which update route should be used to edit the selected resource.

``` yaml
actions:
    update:
        type: update
        route: my_ressource_update_route
```
