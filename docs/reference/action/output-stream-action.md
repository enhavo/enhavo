## Output Stream

This action is a specified version of the
`modal-action <modal_action>`{.interpreted-text role="ref"}. The route
defines the controller action that generates the output stream of the
current resource.


<ReferenceTable
type="output_stream"
className="Enhavo\Bundle\AppBundle\Action\Type\OutputStreamActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="output_stream" :required="true" />
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

### route {#route_output_stream}

**type**: `string`

The route defines the action that generates the output stream of the
current resource.

``` yaml
actions:
    output_stream:
        type: output_stream
        route: my_output_stream_route
```
