## Preview

Opens the integrated preview window with three different display modes
for desktop, mobile and tablet.


<ReferenceTable
type="preview"
className="Enhavo\Bundle\AppBundle\Action\Type\PreviewActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="preview" :required="true" />
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


### route {#route_preview}

Defines which preview route should be used to open the preview view.

``` yaml
actions:
    preview:
        type: preview
        route: my_preview_route
```
