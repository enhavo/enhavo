## Image

The image filter handle cut and resize for images.

<ReferenceTable
type="image"
className="Enhavo\Bundle\MediaBundle\Filter\Filter\ImageFilter"
>
<template v-slot:options>
    <ReferenceOption name="width" type="image" />,
    <ReferenceOption name="height" type="image" />,
    <ReferenceOption name="max_width" type="image" />,
    <ReferenceOption name="max_height" type="image" />,
    <ReferenceOption name="format" type="image" />,
    <ReferenceOption name="jpeg_quality" type="image" />,
    <ReferenceOption name="png_compression_level" type="image" />,
    <ReferenceOption name="crop_width" type="image" />,
    <ReferenceOption name="crop_height" type="image" />,
    <ReferenceOption name="crop_x" type="image" />,
    <ReferenceOption name="crop_y" type="image" />
</template>
</ReferenceTable>


### width {#width_image}

**type**: `integer`

Define the width for the image, the image will be resize exactly to this
size. It depends on the [height](#height) and [maxHeight](#maxheight)
option if the image will scaled.

### height {#height_image}

**type**: `integer`

Define the height for the image, the image will be resize exactly to
this size. It depends on the [width](#width) and [maxWidth](#maxwidth)
option if the image will scaled.

### max_width {#max_width_image}

**type**: `integer`

Define the max width for the image, the image will only be resize to the
value if the max value is reached.

### max_height {#max_height_image}

**type**: `integer`

Define the max height for the image, the image will only be resize to
the value if the max value is reached.

### mode {#mode_image}

**type**: `string`

The possible values are `inset` and `outbound`.
The default value is `outbound` The mode is only used if
width and height are set. If the image need to be scaled and the sizes
are not fit, the `inset` says that the image will be set
into the new size, and you will see the full picture, that means also
that you have spaces on the edges. If you set the value to
`outbound` the picture will be cut on the edges and fit to
the size.

### format {#format_image}

**type**: `string`

Define the output format. That can be `jpg`,
`png` and `gif`. If no format is set, the format
will be the same as the input image.

### jpeg_quality {#jpeg_quality_image}

**type**: `integer`

Set the quality if the output format is jpg. The value can be between 1
and 100. The default value is 75.

### png_compression_level {#png_compression_level_image}

**type**: `integer`

Set the compression level if the output format is png. The value can be
between 1 and 9. The default value is 7.

### crop_width {#crop_width_image}

**type**: `integer`

Set the width of the crop window.

### crop_height {#crop_height_image}

**type**: `integer`

Set the height of the crop window.

### crop_x {#crop_x_image}

**type**: `integer`

Set the x position of the crop window. 0 is the top of the image.

### crop_y {#crop_y_image}

**type**: `integer`

Set the y position of the crop window. 0 is the left edge of the image.
