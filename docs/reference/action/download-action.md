## Download

Downloads the current resource. The Resource has to implement the
`Enhavo\\Bundle\\MediaBundle\\Model\\FileInterface`{.interpreted-text
role="class"}

<ReferenceTable
type="download"
className="Enhavo\Bundle\AppBundle\Action\Type\DownloadActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="download" :required="true" />
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

### route {#route_download}

**type**: `string`

Defines which route should be used to download the selected resource.

``` yaml
actions:
    download:
        type: download
        route: my_download_route
```



### ajax {#ajax_download}

**type**: `boolean`

If the value is true, the download request call is executed via
\"Ajax\"-Call in the background.

``` yaml
actions:
    download:
        type: download
        route: my_download_route
        ajax: true
```
