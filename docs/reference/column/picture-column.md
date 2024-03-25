## Picture

Show a thumbnail


<ReferenceTable
type="picture"
className="Enhavo\Bundle\MediaBundle\Column\PictureColumn"
>
<template v-slot:options>
    <ReferenceOption name="property" type="label" :required="true" />
    <ReferenceOption name="picture_width" type="label" />
    <ReferenceOption name="picture_height" type="label" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="condition" />,
    <ReferenceOption name="width" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="component" />
</template>
</ReferenceTable>


### picture_height

**type**: integer

Define the height of the thumbnail in pixels. The default value is `60`.
This value will influence the height of the whole table row.

```yaml
columns:
    myColumn:
        picture_height: 60
        # ... further option
```


### picture_width

**type**: integer

Define the width of the thumbnail in pixels. The default value is `60`.
If the column the column is in is smaller than this value, the thumbnail
will be resized to fit the column.

```yaml
columns:
    myColumn:
        picture_width: 60
        # ... further option
```



