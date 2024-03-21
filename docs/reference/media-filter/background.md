## Background

Set a background color for the image. This is usefully for images with
transparency.

<ReferenceTable
type="background"
className="Enhavo\Bundle\MediaBundle\Filter\Filter\BackgroundFilter"
>
<template v-slot:options>
    <ReferenceOption name="format" type="text" />,
    <ReferenceOption name="color" />
</template>
</ReferenceTable>

### format

**type**: `integer`

Define the output format. That can be [jpg]{.title-ref},
[png]{.title-ref} and [gif]{.title-ref}.

### color

**type**: `integer`

Set the background color for the image.
