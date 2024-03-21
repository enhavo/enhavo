## Save

Submits the current form and updates the current resource form view.

<ReferenceTable
type="save"
className="Enhavo\Bundle\AppBundle\Action\Type\SaveActionType"
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

### route {#route_save}

**type**: `string`

Define the save route where to send the current form. If you leave that
parameter, the form will send to the default action of the form. If the
passed resource has already an id, that id will also passed as parameter
to the generate url.

``` yaml
actions:
    save:
        type: save
        route: my_save_route
```

